<?php

require "./translator.php";

class Translate_config implements TranslatorConfigItf {

    public function get_translation_directory() {
        return "./translate/";
    }

}

class Translate_lang implements LanguageItf {

    public function get_allowed_languages() {
        return array("fr", "en");
    }

    public function get_current_language() {
        return "fr";
        /* could be used with $_GET or other stuff */
    }

    public function get_default_language() {
        $k = $this->get_allowed_languages();
        return $k[0];
    }

}


function t($text,$args=array()){
    $translator = new Translator(new Translate_lang(), new Translate_config());;
    echo $translator->translate($text, $args);
}