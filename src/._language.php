<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License     
=====================================================================
This web app needs just Apache, PHP (7.4->8.3) and MySQL to work.
---------------------------------------------------------------------
This file contains all the logic needed to set language labels 
-
v1.4.0 - Aldo Prinzi - 03 Mar 2025
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

function getSessionLanguage(){
    $lang="en";
    if (isset($_SESSION["lang"]))
        $lang=$_SESSION["lang"];
    return $lang;
}


function setNewLanguage($lang){
    $_SESSION["lang"]=$lang;
    $lngBtn="<form action='setlang' method='post'><center>";
    switch ($lang){
        case "en": 
            $lngBtn.="<table><tr><td class='langTd'><label class='langLbl'>EN</label></td><td>&nbsp;</td><td class='langTd'><input class='langSbm' type='submit' name='lang' value='it'></td><td>&nbsp;</td><td class='langLbl'><input class='langSbm' type='submit' name='lang' value='fr'></td><td>&nbsp;</td><td><input class='langSbm' type='submit' name='lang' value='sp'></td><td>&nbsp;</td><td class='langTd'><input class='langSbm' type='submit' name='lang' value='de'></td></tr></table>";
            break;
        case "it": 
            $lngBtn.="<table><tr><td class='langTd'><input class='langSbm' type='submit' name='lang' value='en'></td><td>&nbsp;</td><td class='langTd'><label class='langLbl'>IT</label></td><td>&nbsp;</td><td class='langLbl'><input class='langSbm' type='submit' name='lang' value='fr'></td><td>&nbsp;</td><td><input class='langSbm' type='submit' name='lang' value='sp'></td><td>&nbsp;</td><td class='langTd'><input class='langSbm' type='submit' name='lang' value='de'></td></tr></table>";
            break;
        case "fr": 
            $lngBtn.="<table><tr><td class='langTd'><input class='langSbm' type='submit' name='lang' value='en'></td><td>&nbsp;</td><td class='langTd'><input class='langSbm' type='submit' name='lang' value='it'></td><td>&nbsp;</td><td class='langTd'><label class='langLbl'>FR</label></td><td>&nbsp;</td><td><input class='langSbm' type='submit' name='lang' value='sp'></td><td>&nbsp;</td><td class='langTd'><input class='langSbm' type='submit' name='lang' value='de'></td></tr></table>";
            break;
        case "sp": 
            $lngBtn.="<table><tr><td class='langTd'><input class='langSbm' type='submit' name='lang' value='en'></td><td>&nbsp;</td><td class='langTd'><input class='langSbm' type='submit' name='lang' value='it'></td><td>&nbsp;</td><td class='langLbl'><input class='langSbm' type='submit' name='lang' value='fr'></td><td>&nbsp;</td><td class='langTd'><label class='langLbl'>SP</label></td><td class='langTd'><input class='langSbm' type='submit' name='lang' value='de'></td></tr></table>";
            break;
        case "de": 
            $lngBtn.="<table><tr><td class='langTd'><input class='langSbm' type='submit' name='lang' value='en'></td><td>&nbsp;</td><td class='langTd'><input class='langSbm' type='submit' name='lang' value='it'></td><td>&nbsp;</td><td class='langTd'><input class='langSbm' type='submit' name='lang' value='fr'></td><td>&nbsp;</td><td><input class='langSbm' type='submit' name='lang' value='sp'></td><td>&nbsp;</td><td class='langTd'><label class='langLbl'>DE</label></td></tr></table>";
            break;
    }
    $_SESSION["langButtons"]=$lngBtn."</center></form>";
}