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

// Remove slashes from the URI
$uri=str_replace("/","",$_SERVER["REQUEST_URI"]);

// Initialize user agent
$userAgent="";
if (isset($_SERVER["HTTP_USER_AGENT"])){
    $userAgent=$_SERVER["HTTP_USER_AGENT"];
    // Handle IFTTT protocol calls
    if (stripos($userAgent,"IFTTT-Protocol")===0){
        handleIFTTTCall();
        exit;
    }
}

// Initialize variables
$header = "";
$content="";
$showPage=true;
$showApi=false;
$userData=[];
if (isset($_SESSION["user"]))
    $userData=$_SESSION["user"];
$req=[];

// Check if the request is for the API
if ((stripos($uri, "api.php") !== false) || ($_SERVER["REDIRECT_URL"]=="/api") || ($_SERVER["SCRIPT_NAME"]=="/api") || ($_SERVER["SCRIPT_NAME"]=="/api.php"))
    $uri = "api";
$uri=explode("?",$uri)[0];

// Get the referrer URL
$back="/";
if (isset($_SERVER["HTTP_REFERER"]))
    $back=$_SERVER["HTTP_REFERER"];

// Initialize variables for URI changes
$changeduri="";
$newuri="";

// Handle different URIs
switch ($uri){
    case "setlang":
        // Set the language
        if (isset($_POST["lang"]))
            $_SESSION["lang"]=strtolower($_POST["lang"]);
        setNewLanguage($_SESSION["lang"]);
        header("Location: ".$back);
        die();
    case "_pls_fnc_newapikey":
        // Assign a new API key
        $usr=new SLUsers();
        $usr->assignNewApiKey();
        header("Location: ".$back);
        die();
    case "api":
        // Handle API calls
        $db = new Database();
        $res=$db->connect();
        $showPage=false;
        $showApi=true;
        replyToApiCall($db);
        break;
    case "_create_icon":
        // Create an icon
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
    case "_pls_fnc_dash":
        if (!empty($userData) && is_array($userData) && $userData["active"]>0 && $userData["is_admin"]>0){
            include_once '../src/._dashboard.php';
            $header = "<h3>DASHBOARD</h3>";
            $content=getDashboard();    
            break;
        } 
    case "_pls_fnc_login":   
        // Handle login
        $header = "Short Link - Login";
        $pwd=trim($_POST["password"]);
        $usr=trim($_POST["userid"]);
        if ((is_string($pwd) && strlen($pwd)<20 && strlen($pwd)>7) 
        && (is_string($usr) && strlen($usr)<60 && strlen($usr)>5)){
            $UM=new UserManager();
            $_SESSION["user"]=[];
            $user=$UM->authenticate($usr,$pwd);
            if ($user["cust_id"]>0){
                if (stripos($back,"/_pls_fnc_login")!==false)
                    $back="/";
                if (!isset($_SESSION["dvalu"]) || trim($_SESSION["dvalu"])=="" ||(isset($_SESSION["dvalu"]) && stripos($_SESSION["dvalu"],"_pls_fnc_login")!==false))
                    header("Location: ".$back, true);
                else
                    header("Location: ".$_SESSION["dvalu"], true);
                exit();
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
        // Change short code
        if (!empty($userData) && is_array($userData) && $userData["active"]>0){
            $header = "Short Link - Change short code";
            $ret=changeShortCode();
            $content=$ret[1];    
            $changeduri=$ret[0];
        } 
    case "_pls_fnc_shortinfo":
        // Display short info
        if (!empty($userData) && is_array($userData) && $userData["active"]>0){
            $header = "Short Link - Link info";
            $content.=getShortInfoDisplay($userData["cust_id"],$changeduri);
        } else {
            $header=handleAuth(); $content=getLoginForm();
        }
        break;
    case "_pls_fnc_shorten":
        // Shorten a link
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
        // Handle forgot password
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
        break;
    case "_pls_fnc_register":
        // Handle user registration
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
        // Handle password change
        $UM=new UserManager();
        if ($UM->handleChangePass()){
            $header = "Short Link - Password changed";
            $content="<h2>Password changed</h2><p>Your password has been changed.</p>";
            //$content.=getLoginForm();
            $content=getShortenContent($newuri)."<br>&nbsp;<br>".getUserContent();
        } else {
            $header = "Short Link - error";
            $content="<h2>Unknown error!</h2><p>Can't change password.</p>";
        }
        break;
    case "_pls_fnc_handleuserdata":
        // Handle user data
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
        // Remove short info
        if (!empty($userData) && is_array($userData) && $userData["active"]>0){
            $header = "Short Link - Delete";
            $content=delShortData();
        } else {
            $header=handleAuth(); $content=getLoginForm();
        }
        break;
    case "_pls_fnc_logout":   
        // Handle logout
        $_SESSION["user"]=[];
        header("Location: /", true);
    case "":
    case "index.htm":
    case "index.php":
    case "_pls_fnc_user":
        // Display index content
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
        // Handle favicon request
        $ret=getFavicon();
        die ($ret);
    default:
        // If no matching case, do not show the page
        $showPage=false;
        break;
}
//=====================================================================

// If the request is not a shortened link, display the HTML page
if ($showPage){
    // extract the calls data
    if (!empty($userData) && is_array($userData))
        recInnerCall($userData["cust_id"]);
    else
        recInnerCall(0);
    // -------------------------------------
    include 'html/.header.php';
    echo "<div class='title_header'>$header</div>";
    echo "<div style='margin-top:20px' class='container'>".$content."</div>";
    include 'html/.footer.php';
} else if (!$showApi) {
    // If not a page, it is a shortened link
    execRedirect($uri);
}

//=====================================================================
// Functions for handling page content
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
            ignore_user_abort(true);
            http_response_code(307);
            header("Location: ".$res["uri"]);
            flush();
            $uri = $res["uri"];
            $log = $res["log"];
            // Register shutdown function without parameters
            register_shutdown_function(function () use ($uri, $log) {
                sleep(1);
                callIfThisEvent($uri, $log);
            });            
            exit;
        }
        // Handle 410 Gone error
        $res='<div class="container404"><div class="copy-container404 center-xy404"><p class="p404"><span style="font-size:2em;font-weight:900">'.$uri.' ???</span><br>410: Gone -or- page not found.</p><span class="handle404"></span></div></div>';
        http_response_code(410);
        die( '<html class="html404"><head><title>Page not found (hex 32768)</title><link rel="stylesheet" type="text/css" href="/assets/site.css"></head><body class="body404">'.$res.'</body></html>');
    }
}

