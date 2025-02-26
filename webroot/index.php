<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024-2025 - Aldo Prinzi
      Open source project - under MIT License
=====================================================================
This web app needs just Apache, PHP (74->8.3) and MySQL to work.
---------------------------------------------------------------------
This file contains all the logic, the front-end logic and display 
logic in just one file.
-
v1.4.0 - Aldo Prinzi - 03 Mar 2025
---------
UPDATES
---------
2025.02.24 - Added Ip2Location database support
             Added user management and control
             Added logout function
2024.12.23 - Added qr-code generation for the short link, using an
             api server from Fundata GmbH - Karlsruhe (DE):
             https://goqr.me/api/doc/
2024.12.26 - Added calls logger 
2024.12.29 - Added calls logger ip geolocalisation
2024.12.30 - exported all the complexity to specific /src files
2024.01.25 - initial user management and control
2024.02.13 - v1.3.1
             Added 404 reply for not found short links
             Added control over statistics on short link not found
             Added graphic display for statistics using CHART.JS
             Added base database records
             Added cotrol over "about us" link as a short link
=====================================================================
*/
include '../src/._loadenv.php';
include '../src/._apicalls.php';
include '../src/._database.php';
include '../src/._frontend.php';
include '../src/._geolocalize.php';
include '../src/._shortdata.php';
include '../src/._users.php';
include '../src/._usermanager.php';
include '../src/._language.php';
//=====================================================================
$uri=str_replace("/","",$_SERVER["REQUEST_URI"]);
$header = "";
$content="";
$showPage=true;
$showApi=false;
$userData=[];
if (isset($_SESSION["user"]))
    $userData=$_SESSION["user"];
$req=[];
if ((stripos($uri, "api.php") !== false) || ($_SERVER["REDIRECT_URL"]=="/api") || ($_SERVER["SCRIPT_NAME"]=="/api") || ($_SERVER["SCRIPT_NAME"]=="/api.php"))
    $uri = "api";
$uri=explode("?",$uri)[0];

$back="/";
if (isset($_SERVER["HTTP_REFERER"]))
    $back=$_SERVER["HTTP_REFERER"];

$changeduri="";
$newuri="";
switch ($uri){
    case "setlang":
        if (isset($_POST["lang"]))
            $_SESSION["lang"]=strtolower($_POST["lang"]);
        setNewLanguage($_SESSION["lang"]);
        header("Location: ".$back);
        die();
    case "_this_prj_newapikey":
        $usr=new SLUsers();
        $usr->assignNewApiKey();
        header("Location: ".$back);
        die();
    case "favicon.ico":
        $ret=getFavicon();
        die ($ret);
    case "api":
        $db = new Database();
        $res=$db->connect();
        $showPage=false;
        $showApi=true;
        replyToApiCall($db);
        break;
    case "_this_prj_login":   
        $header = "Short Link - Login";
        $pwd=trim($_POST["password"]);
        $usr=trim($_POST["userid"]);
        if ((is_string($pwd) && strlen($pwd)<20 && strlen($pwd)>7) 
        && (is_string($usr) && strlen($usr)<60 && strlen($usr)>5)){
            $_SESSION["user"]=[];
            $user=new SLUsers($usr,$pwd);
            if ($user->isLogged()){
                if (stripos($back,"/_this_prj_login")!==false)
                    $back="/";
                if (!isset($_SESSION["dvalu"]) || trim($_SESSION["dvalu"])=="")
                    header("Location: ".$back, true);
                else
                    header("Location: ".$_SESSION["dvalu"], true);
                exit();
            } else 
                $content=getLoginForm($usr);
        } else 
            $content=getLoginForm("");
        break;
    case "_this_prj_changecode":
        if (!empty($userData) && $userData["active"]>0){
            $header = "Short Link - Change short code";
            $ret=changeShortCode();
            $content=$ret[1];    
            $changeduri=$ret[0];
        } 
    case "_this_prj_shortinfo":
        if (!empty($userData) && $userData["active"]>0){
            $header = "Short Link - Link info";
            $content.=getShortInfoDisplay($userData["cust_id"],$changeduri);
        } else {
            $_SESSION["dvalu"]=$_SERVER["REQUEST_URI"];
            $header = "Short Link - Autenticate";
            $content=getLoginForm();
        }
        break;
    case "_this_prj_shorten":
        if (!empty($userData) && $userData["active"]>0){
            $header = "Short Link - Link shortened";
            $ret=getShortLinkDisplay("");
            $content=$ret[1];    
            $newuri=getenv("URI").$ret[0];
            $content.=getShortInfoDisplay($userData["cust_id"],$newuri);
        } else {
            $_SESSION["dvalu"]=$_SERVER["REQUEST_URI"];
            $header = "Short Link - Autenticate";
            $content=getLoginForm();
        }
        break;
    case "_this_prj_removeshortinfo":
        if (!empty($userData) && $userData["active"]>0){
            $header = "Short Link - Delete";
            $content=delShortData();
        } else {
            $_SESSION["dvalu"]="/";
            $header = "Short Link - Link info";
            $content=getLoginForm();
        }
        break;
    case "_this_prj_logout":   
        $_SESSION["user"]=[];
        header("Location: /", true);
    case "":
    case "index.htm":
    case "index.php":
    case "_this_prj_user":
        if (!empty($userData) && $userData["active"]>0){
            $header = "Short Link - User";
            $content=getShortenContent($newuri)."<br>&nbsp;<br>".getUserContent();
        } else {
            $content=getIndexContent();
        }
        break;
    case "info":
        if (!empty($userData) && $userData["active"]>0){
            $header = "Short Link - Info";
            $content=getShortInfoContent();
        } else {
            $_SESSION["dvalu"]=$_SERVER["REQUEST_URI"];
            $header = "Short Link - Autenticate";
            $content=getLoginForm();
        }
        break;
    default:
        $showPage=false;
        break;
}
//=====================================================================

// se la request on è un link ridotto, allora sarà una pagina del sito
// quindi se $showPage è true visualizzo l'html della pagina.
if ($showPage){
    include 'html/.header.php';
    echo "<div class='title_header'>$header</div>";
    echo "<div style='margin-top:20px' class='container'>".$content."</div>";
    include 'html/.footer.php';
} else if (!$showApi) {
    // se non è una pagina del sito, allora è un link ridotto
    execRedirect($uri);
}

//=====================================================================
// Funzioni per la gestione dei contenuti delle pagine
//=====================================================================

function execRedirect($uri){
    if (!empty($uri)){
        $db = new Database();
        $res=$db->getFullLink($uri);
        if (empty($res)){
            $res='<div class="container404"><div class="copy-container404 center-xy404"><p class="p404"><span style="font-size:2em;font-weight:900">'.$uri.' ??</span><br>404: page not found.</p><span class="handle404"></span></div></div>';
            http_response_code(404);
            die( '<html class="html404"><head><title>Page not found (hex 32768)</title><link rel="stylesheet" type="text/css" href="/html/site.css"></head><body class="body404">'.$res.'</body></html>');
            //$res=getenv("URI");
        }
        http_response_code(302);
        header("Location: ".$res);
        die( '<html><head><meta http-equiv="refresh" content="0; URL='.$res.'" /></head><body><script>var timer=setTimeout(function(){window.location="'.$res.'"}, 1);</script></body></html>');
    } else {
        $res='<div class="container404"><div class="copy-container404 center-xy404"><p class="p404">404.1: page not found because you don\'t specify one.</p><span class="handle404"></span></div></div>';
        http_response_code(404);
        die( '<html class="html404"><head><title>Page not found (hex 32768)</title><link rel="stylesheet" type="text/css" href="/html/site.css"></head><body class="body404">'.$res.'</body></html>');
    }
}

