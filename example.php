<?php
require './example_includes.php';
$username = "bob";
t("hello my friend!");
echo "\n";
t("your name is %name", array("%name"=>$username));
echo "\n";
t("my name is %name and i'm %age year old", array("%name"=>$name,"%age"=>23));

