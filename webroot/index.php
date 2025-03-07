<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024-2025 - Aldo Prinzi
      Open source project - under MIT License
=====================================================================
This web app needs just Apache, PHP (7.4->8.3) and MySQL to work.
---------------------------------------------------------------------
This file contains all the logic, the front-end logic and display 
logic in just one file.
-
v1.4.1 - Aldo Prinzi - 07 Mar 2025
---------
UPDATES
---------

=====================================================================
*/
include '../src/._loadenv.php';
include '../src/._apicalls.php';
include '../src/._database.php';
include '../src/._frontend.php';
include '../src/._analyze.php';
include '../src/._shortdata.php';
include '../src/._users.php';
include '../src/._usermanager.php';
include '../src/._mailmanager.php';
include '../src/._language.php';
//=====================================================================
$uri=str_replace("/","",$_SERVER["REQUEST_URI"]);

$userAgent="";
if (isset($_SERVER["HTTP_USER_AGENT"])){
    $userAgent=$_SERVER["HTTP_USER_AGENT"];
    if (stripos($userAgent,"IFTTT-Protocol")===0){
        //call from IFTTT
        handleIFTTTCall();
        exit;
    }
}

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
    case "_pls_fnc_newapikey":
        $usr=new SLUsers();
        $usr->assignNewApiKey();
        header("Location: ".$back);
        die();
    case "api":
        $db = new Database();
        $res=$db->connect();
        $showPage=false;
        $showApi=true;
        replyToApiCall($db);
        break;
    case "_create_icon":
        $numicon=0;
        if (isset($_GET["icon"]))
            $numicon=intval($_GET["icon"]);
        if (isset($_SESSION["icons"])){
            $images=$_SESSION["icons"];
            if (!empty($images)){
                header("Content-Type: image/jpeg");
                echo base64_decode($images[$numicon]);
                exit;
            }
        }
        break;
    case "_pls_fnc_login":   
        $header = "Short Link - Login";
        $pwd=trim($_POST["password"]);
        $usr=trim($_POST["userid"]);
        if ((is_string($pwd) && strlen($pwd)<20 && strlen($pwd)>7) 
        && (is_string($usr) && strlen($usr)<60 && strlen($usr)>5)){
            $UM=new UserManager();
            $_SESSION["user"]=[];
            //$user=new SLUsers($usr,$pwd);
            $user=$UM->authenticate($usr,$pwd);
            if ($user["cust_id"]>0){
                if (stripos($back,"/_pls_fnc_login")!==false)
                    $back="/";
                if (!isset($_SESSION["dvalu"]) || trim($_SESSION["dvalu"])=="" ||(isset($_SESSION["dvalu"]) && stripos($_SESSION["dvalu"],"_pls_fnc_login")!==false))
                    header("Location: ".$back, true);
                else
                    header("Location: ".$_SESSION["dvalu"], true);
                exit();
            } else {
                /*
                if ($_SESSION["user"] == "NOTVF"){
                    $content="<div>".lng($_SESSION["loginerr"])."</div><hr>";
                }*/
            }
            $content=getLoginForm($usr);
        } else { 
            if (!empty($userData) && is_array($userData) && $userData["active"]>0){
                $header = "Short Link - Link info";
                $content=getShortenContent($newuri)."<br>&nbsp;<br>".getUserContent();
            } else
                $content=getLoginForm("");
        }
        break;
    case "_pls_fnc_changecode":
        if (!empty($userData) && is_array($userData) && $userData["active"]>0){
            $header = "Short Link - Change short code";
            $ret=changeShortCode();
            $content=$ret[1];    
            $changeduri=$ret[0];
        } 
    case "_pls_fnc_shortinfo":
        if (!empty($userData) && is_array($userData) && $userData["active"]>0){
            $header = "Short Link - Link info";
            $content.=getShortInfoDisplay($userData["cust_id"],$changeduri);
        } else {
            $header=handleAuth(); $content=getLoginForm();
        }
        break;
    case "_pls_fnc_shorten":
        if (!empty($userData) && is_array($userData) && $userData["active"]>0){
            $header = "Short Link - Link shortened";
            $ret=getShortLinkDisplay("");
            $content=$ret[1];    
            $newuri=getenv("URI").$ret[0];
            $content.=getShortInfoDisplay($userData["cust_id"],$newuri);
        } else {
            $header=handleAuth(); $content=getLoginForm();
        }
        break;
    case "_pls_fnc_fgtpass":
        $header = "Short Link - User login";
        $content=getLoginForm($usr);
        if (!empty($userData) && is_array($userData) && $userData["active"]>0){
            $header = "User - Forgot password";
            $content=getForgotPasswordForm ($userData);
        } else {
            $usr=trim($_POST["userid"]);
            if ((is_string($usr) && strlen($usr)<60 && strlen($usr)>5)){
                $header = "Short Link - Forgot password";
                $UM=new UserManager();
                $content=$UM->manageForgotPassword($usr);
            } else {
                $UM=new UserManager();
                $VFC= preg_replace('/[^A-Z0-9]/', '', $_GET["verify"]);
                $ret=$UM->verifyPassLost($VFC);
                if ($ret[0]>0){
                    $header = "Short Link - Change Password";
                    $content=$UM->manageForgotPassword($ret[1],true);
                }
            }
        }
        //
        break;
    case "_pls_fnc_register":
        $au=getenv("accept_users");
        $au=filter_var($au==""?"true":$au, FILTER_VALIDATE_BOOLEAN); 
        if ($au){
            if (!(!empty($userData) && is_array($userData) && $userData["active"]>0)){
                $header = "Short Link User - Register";
                $content=getRegistrationForm();
            } else {
                $header = "Short Link - error";
                $content="<h2>Unknown error!</h2><p>Can't register user.</p>";
            }
        } else {
            $header = "Short Link - users";
            $content="<h2>No new users!</h2><p>This projcet does note accept new users.</p>";
        }
        break;
    case "_pls_fnc_forgotpass":
        $UM=new UserManager();
        if ($UM->handleChangePass()){
            $header = "Short Link - Password changed";
            $content="<h2>Password changed</h2><p>Your password has been changed.</p>";
            $content.=getLoginForm();
        } else {
            $header = "Short Link - error";
            $content="<h2>Unknown error!</h2><p>Can't change password.</p>";
        }
        break;
    case "_pls_fnc_handleuserdata":
        if (!empty($userData) && is_array($userData) && $userData["active"] > 0) {
            $header = "Short Link User - Handle";
            $content = handleUserData();
        } else {
            $_SESSION["dvalu"] = $_SERVER["REQUEST_URI"];
            $header = "Autenticazione richiesta";
            $content = getLoginForm();
        }
        break;
    case "_pls_fnc_removeshortinfo":
        if (!empty($userData) && is_array($userData) && $userData["active"]>0){
            $header = "Short Link - Delete";
            $content=delShortData();
        } else {
            $header=handleAuth(); $content=getLoginForm();
        }
        break;
    case "_pls_fnc_logout":   
        $_SESSION["user"]=[];
        header("Location: /", true);
    case "":
    case "index.htm":
    case "index.php":
    case "_pls_fnc_user":
        $content=getIndexContent();
        if (!empty($userData) && is_array($userData) && $userData["active"]>0){
            $header = "Short Link - User";
            $content=getShortenContent($newuri)."<br>&nbsp;<br>".getUserContent();
        } else {
            if (isset($_GET["verify"])){
                $UM=new UserManager();
                $VFC= preg_replace('/[^A-Z0-9]/', '', $_GET["verify"]);
                if ($UM->verifyEmail($VFC)){
                    $header = "Short Link - Autenticate";
                    $content="<div>".lng("email_verified")."</div><hr>".getLoginForm();
                }
            } else {
                $header = "Short Link - Home";
                $_SESSION["user"]=[];
                $content=getIndexContent();          
            }
        }
        break;
    case "favicon":
    case "favicon.ico":
        $ret=getFavicon();
        die ($ret);
    /*
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
    */
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

function handleAuth(){
    $_SESSION["dvalu"] = $_SERVER["REQUEST_URI"];
    return "Short Link - Autenticate";
}

function execRedirect($uri){
    if (!empty($uri)){
        $db = new Database();
        $res=$db->getFullLink($uri);
        if (!is_null($res) && !empty($res["uri"])){
            /*
            300 Multiple Choices
            301 Moved Permanently
            302 Found (Previously "Moved temporarily")
            303 See Other (since HTTP/1.1)
            304 Not Modified
            305 Use Proxy (since HTTP/1.1)
            306 Switch Proxy
            307 Temporary Redirect (since HTTP/1.1)
                In this case, the request should be repeated with another URI; however, future requests should still use the original URI. 
                In contrast to how 302 was historically implemented, the request method is not allowed to be changed when reissuing the original request. 
                For example, a POST request should be repeated using another POST request.
            308 Permanent Redirect
            */
            ignore_user_abort(true);
            http_response_code(307);
            header("Location: ".$res["uri"]);
            flush();
            $uri = $res["uri"];
            $log = $res["log"];
            // Registra la funzione di shutdown senza parametri
            register_shutdown_function(function () use ($uri, $log) {
                sleep(1);
                callIfThisEvent($uri, $log);
            });            
            exit;
        }
        /*
            400 Bad Request
            401 Unauthorized
            402 Payment Required
            403 Forbidden
            404 Not Found
            405 Method Not Allowed
            406 Not Acceptable
            407 Proxy Authentication Required
            408 Request Timeout
            409 Conflict
            410 Gone
                Indicates that the resource requested was previously in use but is no longer available and will not be available again. 
                This should be used when a resource has been intentionally removed and the resource should be purged. 
                Upon receiving a 410 status code, the client should not request the resource in the future. 
                Clients such as search engines should remove the resource from their indices. 
                Most use cases do not require clients and search engines to purge the resource, and a "404 Not Found" may be used instead.
        */
        $res='<div class="container404"><div class="copy-container404 center-xy404"><p class="p404"><span style="font-size:2em;font-weight:900">'.$uri.' ???</span><br>410: Gone -or- page not found.</p><span class="handle404"></span></div></div>';
        http_response_code(410);
        die( '<html class="html404"><head><title>Page not found (hex 32768)</title><link rel="stylesheet" type="text/css" href="/assets/site.css"></head><body class="body404">'.$res.'</body></html>');
        /*
            411 Length Required
            412 Precondition Failed
            413 Payload Too Large
            414 URI Too Long
            415 Unsupported Media Type
            416 Range Not Satisfiable
            417 Expectation Failed
            418 I'm a teapot (RFC 2324, RFC 7168) !!! :)
            419 [unofficial] Page Expired (Laravel Framework)
            420 [unofficial] Method Failure (Spring Framework) - Enhance Your Calm (Twitter)
            421 Misdirected Request
            422 Unprocessable Content
            423 Locked (WebDAV; RFC 4918)
            424 Failed Dependency (WebDAV; RFC 4918)
            425 Too Early (RFC 8470)
            426 Upgrade Required
            428 Precondition Required (RFC 6585)
            429 Too Many Requests (RFC 6585)
            430 [unofficial] Request Header Fields Too Large (Shopify) - Shopify Security Rejection (Shopify)
            431 Request Header Fields Too Large (RFC 6585)
            450 [unofficial] Blocked by Windows Parental Controls (Microsoft)
            451 navailable For Legal Reasons (RFC 7725)
            498 [unofficial] Invalid Token (Esri)
            499 [unofficial] Token Required (Esri)
        */
    }
}

