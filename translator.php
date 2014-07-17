/**
 *
 * @author decima <henri@larget.fr>
 * please check the LICENCE file for more details about how you can use my work :-)
 **/

interface LanguageItf{
  public function get_current_language();
  public function get_allowed_languages();
  public function get_default_language();
}

class Translator{
  const _TRANSLATIONS_DIRECTORY="./translates/";
  const _DEFAULT_LANG="fr";
  private $_languageItf;
  public function __construct($language_object){
    $this->_languageItf = $language_object;
  }
  
  /* next later :-) */
}
