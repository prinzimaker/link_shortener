<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024 - Aldo Prinzi
      Open source project - under MIT License
=====================================================================
This web app needs just Apache, PHP (74->8.3) and MySQL to work.
---------------------------------------------------------------------
This file contains all the logic, the front-end logic and display 
logic in just one file.
-
v1.2.1 - Aldo Prinzi - 30 Dic 2024
---------
UPDATES
---------
2024.12.23 - Added qr-code generation for the short link, using an
             api server from Fundata GmbH - Karlsruhe (DE):
             https://goqr.me/api/doc/
2024.12.26 - Added calls logger 
2024.12.29 - Added calls logger ip geolocalisation
2024.12.30 - exported all the complexity to specific /src files
=====================================================================
*/
include '../src/._loadenv.php';
include '../src/._apicalls.php';
include '../src/._connect.php';
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

switch ($uri){
    case "setlang":
        setNewLanguage($_SESSION["lang"]=="en"?"it":"en");
        header("Location: /");
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
    case "login":   
        $header = "Short Link - Login";
        $user=new SLUsers($_POST["userid"],$_POST["password"]);
        if ($user->isLogged()){
            header("Location: ".$_SESSION["dvalu"], true, 302);
            exit();
        } else 
            $content=getLoginForm($_POST["userid"]);
        break;
    case "user":
        if (!empty($userData) && $userData["active"]>0){
            $header = "Short Link - User";
            $content=getUserContent($uri);
        } else {
            $_SESSION["dvalu"]=$_SERVER["REQUEST_URI"];
            $header = "Short Link - Autenticate";
            $content=getLoginForm();
        }
        break;
    case "shortinfo":
        if (!empty($userData) && $userData["active"]>0){
            $header = "Short Link - Link info";
            $content=getShortInfoDisplay();
        } else {
            $_SESSION["dvalu"]=$_SERVER["REQUEST_URI"];
            $header = "Short Link - Autenticate";
            $content=getLoginForm();
        }
    break;
    case "shorten":
        if (!empty($userData) && $userData["active"]>0){
            $header = "Short Link - Link shortened";
            $content=getShortLinkDisplay();
        } else {
            $_SESSION["dvalu"]=$_SERVER["REQUEST_URI"];
            $header = "Short Link - Autenticate";
            $content=getLoginForm();
        }
        break;
    case "":
    case "index.htm":
    case "index.php":
        $header = "Short Link - Home";
        $content=getShortenContent("")."<br>&nbsp;<br>".getHomeContent($uri);
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
            $res=getenv("URI");
        }
        http_response_code(302);
        header("Location: ".$res);
        die( '<html><head><meta http-equiv="refresh" content="0; URL='.$res.'" /></head><body><script>var timer=setTimeout(function(){window.location="'.$res.'"}, 1);</script></body></html>');
    } else {
        http_response_code(404);
        die( '<html><head><meta http-equiv="refresh" content="0; URL=/" /></head><body><script>var timer=setTimeout(function(){window.location="/"}, 1);</script></body></html>');
    }
}

