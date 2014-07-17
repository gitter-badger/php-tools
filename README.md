easy-translate
==============
 > Les README.md, c'est pas pour les Dahus.
 > Julien Rosa

Thank you Julien to point the fact that I didn't write my readme file. So now on, i'll write it.

##DESCRIPTION##

The purpose of this project is to create an easy way to manage multiple languages on a php application (website, anything else)


###how to install it###
==============

first you need to declare two classes which implements TranslatorConfigItf and LanguageItf (you can use example_includes.php and it will show you how to use it)
after that, you should define a short function (as my t() function in my example_includes.php)
That's it.

Just be sure the folder you specified is writable.

###how to use it###
==============

this is the best.
first at all, every text that appears in your application should be t("TEXT"); or t("TEXT %n", array("%n"=>42));
when the function with the args is called, it will create an entry in the specified folder.
You just need to edit the created/edited file and replace it with the translations you need.


###Example###
==============

t("text");

t("hello %user!", array("%user"=>$username));
the second example permits people to translate a part of a text and add some vars.
For example, if you want to use this example in french, you can just put "bonjour %user!", on even in chinese or any other language.