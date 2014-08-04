<?php

/**
 *
 * @author decima <henri@larget.fr>
 * please check the LICENCE file for more details about how you can use my work :-)
 * */
interface LanguageItf {

    public function get_current_language();

    public function get_allowed_languages();

    public function get_default_language();
}

interface TranslatorConfigItf {

    public function get_translation_directory();
}

class Translator {

    private $_language, $_config;

    public function __construct(LanguageItf $language_object, TranslatorConfigItf $config_object) {
        $this->_language = $language_object;
        $this->_config = $config_object;
        if (!file_exists($this->_config->get_translation_directory()) || !is_dir($this->_config->get_translation_directory())) {
            mkdir($this->_config->get_translation_directory());
        }
    }

    public function translate($text, $args = array()) {
        $lang = $this->_language->get_default_language();
        if (in_array($this->_language->get_current_language(),$this->_language->get_allowed_languages())) {
            $lang = $this->_language->get_current_language();
        }
        $filename = $this->_config->get_translation_directory() . '/' . $lang . '.php';
        $translates = array();
        if (file_exists($filename)) {
            require $filename;
        } else {
            file_put_contents($filename, "<?php\n\$translates = array();");
        }
        if (isset($translates[md5($text)])) {
            $text_translated = $translates[md5($text)];
        } else {
            $content_translation = file_get_contents($filename);
            $content_translation .= "\n\$translates[\"" . md5($text) . "\"]=\"" . $text . "\";";
            file_put_contents($filename, $content_translation);
            $text_translated = $text;
        }
        return $this->replace_args($text_translated, $args);
    }

    private function replace_args($text, $args = array()) {
        foreach ($args as $key => $value) {
            $text = str_replace($key, $value, $text);
        }
        return $text;
    }

}
