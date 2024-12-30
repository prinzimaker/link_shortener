<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License     
=====================================================================
This web app needs just Apache, PHP (74->8.3) and MySQL to work.
---------------------------------------------------------------------
This file contains all the logic needed to set language labels 
-
v1.2.1 - Aldo Prinzi - 30 Dic 2024
=====================================================================
*/
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
setSessionLanguage();
function setSessionLanguage($lang=""){
    if ($lang=="" && isset($_SESSION["lang"]))
        $lang=$_SESSION["lang"];
    if ($lang==""){ 
        $lang="en";
        $_SESSION["lang"]="en";
    }
    include_once __DIR__ . "/langs/".$lang.".php";
    setNewLanguage($lang);
}

function setNewLanguage($lang){
    $_SESSION["lang"]=$lang;
    $subm="en";
    $lngBtn="<form action='setlang' method='post'><center>";
    if ($lang=="en"){
        $subm="it";
        $lngBtn.="<table><tr><td class='langTd'><label class='langLbl'>en</label></td><td>&nbsp;</td><td class='langTd'><input class='langSbm' type=submit value='it'></td></tr></table>";
    } else {
        $lngBtn.="<table><tr><td class='langTd'><input class='langSbm' type=submit value='en'></td><td>&nbsp;</td><td class='langTd'><label class='langLbl'>it</label></td></tr></table>";
    }
    $_SESSION["langButtons"]=$lngBtn."</center></form>";
}