<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License     
=====================================================================
This web app needs just Apache, PHP (7.4->8.3) and MySQL to work.
---------------------------------------------------------------------
This file contains all the front-end html page/form generators 
=====================================================================
*/
// form per lo shorten del link
function getShortenContent($uri){
    return '<form action="_pls_fnc_shorten" method="post">
    <div class="form-group">
        <label for="uri">URI</label>
        <input type="text" class="input-text" name="uri" placeholder="'.lng("front_insert-long").'" value="'.$uri.'">
    </div>
    <button type="submit" class="btn btn-primary">'.lng("front_shorten").'</button>
</form>';
}

function getShortInfoContent($code="",$uri=""){
    if (stripos($uri,getenv("URI"))!==false)
        $uri=substr($uri,strlen(getenv("URI")));
    $ret='<div class="alert alert-warning"><form action="_pls_fnc_changecode"  method="post"><input type="hidden" name="shortcode" value="'.$code.'">
    <table><tr><td><label>'.lng("front_reduced-link").':&nbsp;<strong>
    <a class="input-text" id="link1" style="background-color:#fff" target="_blank" href="'.getenv("URI").$uri.'">'.getenv("URI").$uri.'</a></strong></label>&nbsp;
    <button class="btn btn-small btn-warning" onclick=\'event.preventDefault();copyData("link1","itext","'.lng("front_copied-link").'","'.lng("front_copy-error").'")\'>'.lng("copy").'</button>
    <td>&nbsp;-&nbsp;</td><td><label>'.lng("change_link_code").'</label></td><td><input type="text" class="input-text" name="newcode" placeholder="" value="'.$code.'"></td>
    <td>&nbsp;</td><td><button type="submit" class="btn btn-primary">'.lng("change").'</button></td></tr></table>
</form></div>';
    return $ret;
}

function getLoginForm($userid=""){
    isset($_SESSION["loginerr"])?$errMsg=lng($_SESSION["loginerr"]):"";
    $_SESSION["loginerr"]="";
    $ret='<div class="auth-div"><div class="login-header"><h3>'.lng("autentication").'</h3></div>
    <form class="auth-form" action="_pls_fnc_login" method="post">
        <input id="hiddensecret" type="hidden" name="secret" value="">
        <div class="form-group"><label for="userid">'.lng("user").'</label>
            <input id="userid" type="text" class="input-text2" name="userid" placeholder="'.lng("user").'" value="'.$userid.'">
            <label for="password">'.lng("password").'</label>
            <input type="password" class="input-text2" name="password" placeholder="'.lng("password").'" value="">
            <br><a class="forgotpass" href="/_pls_fnc_fgtpass">'.lng("forgot_pass").'</a></div>
        <button type="submit" class="btn btn-primary">'.lng("login").'</button>
        <input id="fgtpass" type="hidden" name="forgot_password" value="">
    </form>
    <div class="err-message">'.$errMsg.'</div>
</div><br>
<script>
    document.querySelector(".auth-form").addEventListener("submit", function(event) {
        const passwordInput = document.querySelector("input[name=\'password\']").value.trim();
        if (passwordInput === "") {
            event.preventDefault();
            return false;
        }
    });
    document.querySelector(".forgotpass").addEventListener("click", function(event) {
        event.preventDefault(); 
        const useridInput = document.getElementById("userid").value.trim();
        const fgtpassInput = document.getElementById("fgtpass");
        const form = document.querySelector(".auth-form");
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailRegex.test(useridInput)) {
            fgtpassInput.value = useridInput;
            form.action = "/_pls_fnc_fgtpass"; 
            form.submit();
        } else {
            alert("'.lng("email_needed").'");
        }
    });
</script>
';
    return $ret;
}

function getForgotPasswordForm($userEmail,$verifyCode=""){
    generateRandomIcons();
    return "
        <div class='data-div'>
            <div class='login-header'>".$_SESSION["langButtons"]."<hr>&nbsp;<br><h3>".lng("change_pass_form")."</h3></div>
            <form id='registrationForm' class='auth-form' action='_pls_fnc_forgotpass' method='post'>
                <input id='hiddensecret' type='hidden' name='secret' value=''>
                <div class='form-group'>
                    <label for='userid'>".lng("user")."</label>
                    <div id='userid' type='text' class='input-text2'>".$userEmail."</div></div>
                <div class='form-group'>
                    <label for='password'>" . lng("password") . "</label>
                    <table width='100%'><tr><td>&nbsp;</td><td width='70%'>
                        <input id='password' type='password' class='input-text2' name='password' placeholder='" . lng("password") . "' value=''>
                    </td><td width='28%'><div class='input-text' 'background:#fafafa;font-weight:800' id='passwordStrength' ></div></td></tr></table></div>
                <div class='form-group'>
                    <label for='password_confirm'>" . lng("repeat_password") . "</label>
                    <table width='100%'><tr><td>&nbsp;</td><td width='70%'>
                        <input id='password_confirm' type='password' class='input-text2' name='password_confirm' placeholder='" . lng("repeat_password") . "' value=''>
                    </td><td width='28%'><div class='input-text' style='background:#fafafa;font-weight:800' id='passwordMatchMessage'></div></td></tr></table></div>
                <input type='hidden' name='verifycode' value='".$verifyCode."'>
"._addPassFormChecks();
}

function handleUserData() {
    // Verifica se l'utente è loggato
    if (!isset($_SESSION["user"]) || empty($_SESSION["user"])) {
        return getLoginForm();
    }
    
    $msg = "";
    // Istanzia l'oggetto SLUsers per gestire le operazioni sull'utente
    $userObj = new SLUsers();

    // Se il form è stato inviato, processa l'aggiornamento dei dati o il cambio password
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Aggiornamento dati (profilo)
        if (isset($_POST['updateProfile'])) {
            $descr = isset($_POST['descr']) ? trim($_POST['descr']) : "";
            $email = isset($_POST['email']) ? trim($_POST['email']) : "";
            
            // Il metodo updateUserData() va implementato nella classe SLUsers
            if ($userObj->updateUserData($_SESSION["user"]["cust_id"], $descr, $email)) {
                // Aggiorna anche i dati in sessione
                $_SESSION["user"]["descr"] = $descr;
                $_SESSION["user"]["email"] = $email;
                $msg .= "<div class='alert alert-success'>Dati aggiornati correttamente.</div>";
            } else {
                $msg .= "<div class='alert alert-danger'>Errore durante l'aggiornamento dei dati.</div>";
            }
        }
        
        // Cambio password
        if (isset($_POST['changePassword'])) {
            $oldPassword = isset($_POST['oldPassword']) ? trim($_POST['oldPassword']) : "";
            $newPassword = isset($_POST['newPassword']) ? trim($_POST['newPassword']) : "";
            
            if ($userObj->changePassword($_SESSION["user"]["email"],  $newPassword)) {
                $msg .= "<div class='alert alert-success'>Password cambiata con successo.</div>";
            } else {
                $msg .= "<div class='alert alert-danger'>Errore nel cambio password. Verifica la vecchia password.</div>";
            }
        }
    }
    
    // Recupera i dati correnti dalla sessione per pre-compilare il form
    $descr = isset($_SESSION["user"]["descr"]) ? $_SESSION["user"]["descr"] : "";
    $email = isset($_SESSION["user"]["email"]) ? $_SESSION["user"]["email"] : "";
    
    // Genera il form HTML per l'aggiornamento dei dati utente e il cambio password
    $html = "<h2>Gestione Dati Utente</h2>";
    $html .= $msg;
    $html .= "
        <form method='post' action='_pls_fnc_handleuserdata'>
            <h3>Aggiorna Profilo</h3>
            <div class='form-group'> <label for='descr'>Nome Utente</label>
                <input type='text' name='descr' id='descr' class='input-text2' value='" . htmlspecialchars($descr, ENT_QUOTES) . "'></div>
            <div class='form-group'> <label for='email'>Email</label>
                <input type='email' name='email' id='email' class='input-text2' value='" . htmlspecialchars($email, ENT_QUOTES) . "'></div>
            <button type='submit' name='updateProfile' class='btn btn-primary'>Aggiorna Dati</button>
        </form><hr>
        <form method='post' action='_pls_fnc_handleuserdata'>
            <h3>Cambio Password</h3>
            <div class='form-group'> <label for='oldPassword'>Vecchia Password</label>
                <input type='password' name='oldPassword' id='oldPassword' class='input-text2'></div>
            <div class='form-group'> <label for='newPassword'>Nuova Password</label>
                <input type='password' name='newPassword' id='newPassword' class='input-text2'></div>
            <button type='submit' name='changePassword' class='btn btn-primary'>Cambia Password</button>
        </form>
    ";
    
    return $html;
}
function getRegistrationForm($descr = "", $email = "") 
{
    $_SESSION["icons"] = "";
    $_SESSION["iconNames"] = "";
    $_SESSION["_iconSelect"] = "";

    $userId=isset($_POST["descr"])?$_POST["descr"]:"";
    $userEmail=isset($_POST["email"])?$_POST["email"]:"";
    $userPass=isset($_POST["password"])?$_POST["password"]:"";
    $antiIcon=isset($_POST["icon"])?$_POST["icon"]:"";

    if ($userId!="" && $userEmail!="" && $userPass!="" && $antiIcon!="" && isset($_SESSION["_icon"])){
        if ($antiIcon==$_SESSION["_icon"]){
            $_SESSION["icons"] = "";
            $um=new UserManager();
            $normEmail=$um->normalizeEmail($userEmail);                
            $res=$um->getUserData($normEmail);
            if ($res===false){
                if ($um->registerUser($normEmail,$userPass,$userId))
                    return "<div class='alert alert-success'>".str_replace("{{email}}","<strong>".$userEmail."</strong>",lng("0regok"))."</div>";
            } else 
                return "<div class='alert alert-warning'>".lng("0uexist")."</div>";
        }
    }
    generateRandomIcons();
    return "
        <div class='data-div'>
            <div class='login-header'>".$_SESSION["langButtons"]."<hr>&nbsp;<br><h3>".lng("user_registration")."</h3></div>
            <form id='registrationForm' class='auth-form' action='_pls_fnc_register' method='post'>
                <input id='hiddensecret' type='hidden' name='secret' value=''>
                <div class='form-group'> <label for='descr'>" . lng("user") . "</label>
                    <input id='descr' type='text' class='input-text2' name='descr' placeholder='" . lng("username") . "' value='" . htmlspecialchars($userId, ENT_QUOTES) . "'></div>
                <div class='form-group'> <label for='email'>" . lng("email") . "</label>
                    <input id='email' type='email' class='input-text2' name='email' placeholder='" . lng("email") . "' value='" . htmlspecialchars($userEmail, ENT_QUOTES) . "'></div>
                <div class='form-group'>  <label for='password'>" . lng("password") . "</label>
                    <table width='100%'><tr><td>&nbsp;</td><td width='70%'>
                        <input id='password' type='password' class='input-text2' name='password' placeholder='" . lng("password") . "' value=''>
                    </td><td width='28%'><div class='input-text' style='background:#fafafa;font-weight:800' id='passwordStrength' ></div></td></tr></table> </div>
                <div class='form-group'> <label for='password_confirm'>" . lng("repeat_password") . "</label>
                    <table width='100%'><tr><td>&nbsp;</td><td width='70%'>
                        <input id='password_confirm' type='password' class='input-text2' name='password_confirm' placeholder='" . lng("repeat_password") . "' value=''>
                    </td><td width='28%'><div class='input-text' style='background:#fafafa;font-weight:800' id='passwordMatchMessage'></div></td></tr></table> </div>
"._addPassFormChecks();
}

function _addPassFormChecks(){
    return "
                    <input type='hidden' name='icon' id='icon' value=''>    
                <div class='form-group' style='margin-top:10px;'><center>
                    <div><h3>".$_SESSION["_iconSelect"]."</h3></div> <table><tr>
                    <td><img alt='icon' class='iconbtn' src='https://flu.lu/_create_icon?icon=0' onclick='document.getElementById(\"opt0\").click()'></td><td>&nbsp;</td>    
                    <td><img alt='icon' class='iconbtn' src='https://flu.lu/_create_icon?icon=1' onclick='document.getElementById(\"opt1\").click()'></td><td>&nbsp;</td>    
                    <td><img alt='icon' class='iconbtn' src='https://flu.lu/_create_icon?icon=2' onclick='document.getElementById(\"opt2\").click()'></td><td>&nbsp;</td>    
                    <td><img alt='icon' class='iconbtn' src='https://flu.lu/_create_icon?icon=3' onclick='document.getElementById(\"opt3\").click()'></td><td>&nbsp;</td>    
                    <td><img alt='icon' class='iconbtn' src='https://flu.lu/_create_icon?icon=4' onclick='document.getElementById(\"opt4\").click()'></td><td>&nbsp;</td>    
                    <td><img alt='icon' class='iconbtn' src='https://flu.lu/_create_icon?icon=5' onclick='document.getElementById(\"opt5\").click()'></td>    
                    </tr><tr>
                    <td class='icontd'><input name='icoas' onclick='document.getElementById(\"icon\").value=0' class='styled-radio' type='radio' id='opt0'><label for='opt0'></label></td><td>&nbsp;</td>    
                    <td class='icontd'><input name='icoas' onclick='document.getElementById(\"icon\").value=1' class='styled-radio' type='radio' id='opt1'><label for='opt1'></label></td><td>&nbsp;</td>    
                    <td class='icontd'><input name='icoas' onclick='document.getElementById(\"icon\").value=2' class='styled-radio' type='radio' id='opt2'><label for='opt2'></label></td><td>&nbsp;</td>    
                    <td class='icontd'><input name='icoas' onclick='document.getElementById(\"icon\").value=3' class='styled-radio' type='radio' id='opt3'><label for='opt3'></label></td><td>&nbsp;</td>    
                    <td class='icontd'><input name='icoas' onclick='document.getElementById(\"icon\").value=4' class='styled-radio' type='radio' id='opt4'><label for='opt4'></label></td><td>&nbsp;</td>    
                    <td class='icontd'><input name='icoas' onclick='document.getElementById(\"icon\").value=5' class='styled-radio' type='radio' id='opt5'><label for='opt5'></label></td>       
                    </tr></table></center></div>
                <button type='submit' class='btn btn-primary'>" . lng("register") . "</button>
            </form>
        </div>"._addPassFormScripts("registrationForm");
}
function _addPassFormScripts($formName){
    return "
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('".$formName."').addEventListener('submit', function(e) {
                var errorMessages = [];
                var userid =''; var nouserid=false;
                var email ='';  var noemail=false;
                var icon ='';   var noicon=false;
                if (document.getElementById('descr')) userid = document.getElementById('descr').value.trim(); else nouserid=true;
                if (document.getElementById('email')) email = document.getElementById('email').value.trim();  else noemail=true;
                if (document.getElementById('icon')) icon = document.getElementById('icon').value.trim();     else noicon=true;
                var password = document.getElementById('password').value.trim();
                var passwordConfirm = document.getElementById('password_confirm').value.trim();
                if (userid === '' & !nouserid)                      errorMessages.push(\"".lng("0nouid")."\");
                if (email === '' & !noemail)                        errorMessages.push(\"".lng("0noemail")."\");
                if (password === '')                                errorMessages.push(\"".lng("0nopass")."\");
                if (icon === '' & !noicon)                          errorMessages.push(\"".lng("0nospam")."\");
                if (password !== '' && password.length < 8)         errorMessages.push(\"".lng("0smallpass")."\");
                if (!noemail){
                    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (email !== '' && !emailPattern.test(email))  errorMessages.push(\"".lng("0invemail")."\");
                }
                if (errorMessages.length > 0) {
                    alert(errorMessages.join(\"\\n\"));
                    e.preventDefault();
                    return false;
                }
                if (password !== passwordConfirm) {
                    alert(\"".lng("0diffpass")."\");
                    e.preventDefault();
                    return false;
                }
                if (document.getElementById('hiddensecret')){
                    (document.getElementById('hiddensecret').value!=''){
                        e.preventDefault();
                        return false;
                    }
                }
            });
            document.getElementById('password').addEventListener('keyup', function() {
                updatePasswordStrength();
                checkPasswordsMatch();
            });
            document.getElementById('password_confirm').addEventListener('keyup', checkPasswordsMatch);
        });
        function checkPasswordsMatch() {
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirm').value;
            const matchMessage = document.getElementById('passwordMatchMessage');
            if (password === passwordConfirm) {
                matchMessage.style.color = 'green';
                matchMessage.innerText = 'OK';
            } else {
                matchMessage.style.color = 'red';
                matchMessage.innerText = \"".lng("1diffpass")."\";
            }
        }
        function updatePasswordStrength() {
            var password = document.getElementById('password').value;
            var strength = 0;
            if (password.length < 8) {
                strength = 0;
            } else {
                var hasUpper   = /[A-Z]/.test(password);
                var hasLower   = /[a-z]/.test(password);
                var hasDigit   = /\d/.test(password);
                var hasSpecial = /[^A-Za-z0-9]/.test(password);
                if (hasUpper && hasLower && hasDigit && hasSpecial)
                    strength = 2;
                else if ((hasUpper || hasLower) && hasDigit)
                    strength = 1;
                else
                    strength = 0;
            }
            var strengthText = '';
            var elm=document.getElementById('passwordStrength');
            switch (strength) {
                case 0: strengthText = \"".lng("0poor")."\"; elm.style.color = 'red'; break;
                case 1: strengthText = \"".lng("0mean")."\"; elm.style.color = 'orange'; break;
                case 2: strengthText = \"".lng("0strong")."\"; elm.style.color = 'green'; break;
            }
            elm.innerText = strengthText;
            return strength;
        }
    </script>
    ";
}

function getUserContent(){
    $userData="";
    if (isset($_SESSION["user"]))
        $userData=$_SESSION["user"];
    if (empty($userData))
        return;
    $db=new Database();
    $userData=$db->getUserByApiKey($userData["apikey"]);
    $content='
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
        <script>
            $(document).ready(function () {
                $("#userCodesTable").DataTable({"paging": true, "ordering": true, "info": true});
            });
        </script>
        <div id="modal" class="modal hidden appear">
            <form id="changePassForm" class="auth-form" action="_pls_fnc_register" method="post">
                <input id="hiddensecret" type="hidden" name="secret" value="">
                <div class="modal-header">'.strtoupper(lng("change_pass_form")).'
                    <span class="modal-closer" onclick="closemodal()">&times;</span></div>
                <div class="modal-content">
                    <div class="form-group"> <label for="password">' . lng("password") . '</label>
                        <input id="password" type="password" class="input-text2" name="password" value=""><br>
                        <div class="label" style="font-size:0.9em;font-weight:800" id="passwordStrength" ></div></div>
                    <div class="form-group"> <label for="password_confirm">' . lng("repeat_password") . '</label>
                        <input id="password_confirm" type="password" class="input-text2" name="password_confirm" value=""><br>
                        <div class="label" style="font-size:0.9em;font-weight:800" id="passwordMatchMessage"></div> </div> </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Change Password"></div>
            </form> </div>
'._addPassFormScripts("changePassForm").'
        <div class="alert alert-info">
            <table width="100%"><tr><td width="45%"> <label for="userid">'.lng("user").'</label><br>
                <input style="margin-top:3px" id="userid" type="text" class="input-text2" name="userid" placeholder="'.lng("user").'" value="'.$userData["descr"].'"></td>
                <td>&nbsp;</td><td width="55%"><label for="userid">API key</label><br><input style="margin-top:3px" id="apikey" type="text" class="input-text2" name="apikey" placeholder="API key" value="'.$userData["apikey"].'">
                <button class="btn btn-small btn-warning" onclick="event.preventDefault();copyData(\'apikey\',\'\',\''.lng("front_copied-link").'\',\''.lng("front_copy-error").'\')">'.lng("copy").'</button>
                
                
                </td></tr>
                <tr><td><label for="userid">'.lng("email").'</label><br>
                    <input style="margin-top:3px" id="userid" type="text" class="input-text2" name="userid" placeholder="'.lng("user").'" value="'.$userData["email"].'">
                </td><td>&nbsp;</td><td><table><tr><td>
                    <button type="button" class="btn btn-warning" onclick="openmodal()" o-n-click=\'window.location.href="/_pls_fnc_fgtpass"\'>'.lng("change password").'</button>&nbsp;</td><td>
                    <form method="post" action="_pls_fnc_newapikey"><input type="submit" class="btn btn-primary" value="'.lng("new apikey").'"></form></td><td>
                    &nbsp;<button type="button" class="btn btn-secondary" onclick=\'window.location.href="/_pls_fnc_logout"\'>Logout</button></td></tr></table>
                </td></tr></table></div>
        <div class="form-group"><label>User\'s Links</label><div class="userTabLinks"><table id="userCodesTable" class="display"><thead><tr><th>short_id</th><th>&nbsp;</th><th>Uri</th><th>Calls</th><th>Created</th><th>Last call</th></tr></thead><tbody>
    ';
    
    $db = new Database();
    $result = $db->getUserShortDatatable();

    // Costruzione della tabella HTML
    foreach ($result as $row) {

        $fu=$row['full_uri'];
        if (strlen($fu)>65)
            $fu=substr($fu, 0, 65)." ...";
        
        $timestamp = new DateTime($row['last_call']);
        if ($timestamp->format("Y")=="1999"){
            $timestamp="";
        } else {
            $now = new DateTime();
            $diff = $now->diff($timestamp);
            
            // Calcolo dei periodi relativi
            if ($diff->y > 0) {
                $timestamp= $diff->y == 1 ? "one year ago" : $diff->y . " years ago";
            } elseif ($diff->m > 0) {
                $timestamp= $diff->m == 1 ? "one month ago" : $diff->m . " months ago";
            } elseif ($diff->d > 0) {
                $timestamp= $diff->d == 1 ? "one day ago" : $diff->d . " days ago";
            } elseif ($diff->h > 0) {
                $timestamp= $diff->h == 1 ? "one hour ago" : $diff->h . " hours ago";
            } elseif ($diff->i > 0) {
                $timestamp= $diff->i == 1 ? "one minute ago" : $diff->i . " minutes ago";
            } else {
                $timestamp= "right now";
            }
        }
        $created = new DateTime($row['created'] );

        $content.='<tr>';
        $content.= '<td><a href="/_pls_fnc_shortinfo?code='. $row['short_id'] .'">' . $row['short_id'] . '</a></td>';
        $content.= '<td><a href="/_pls_fnc_removeshortinfo?code='. $row['short_id'] .'"><button class="btn btn-small btn-warning">DEL</button></a></td>';
        $content.= '<td><a href="' . getenv("URI"). $row['short_id'] . '" target="_blank">'.htmlspecialchars($fu, ENT_QUOTES).'</a></td>';
        $content.= '<td align="right">' . $row['calls'] . '</td>';
        $content.= '<td>' . $created->format("d/m/y") . '</td>';
        $content.= '<td>' . $timestamp . '</td>';
        $content.='</tr>';
    }
    return $content.'</tbody></table></div></div>';
}

// Contenuto della pagina home
function getUserHomeContent($uri){
    return lng("front_instructions");
}
function getIndexContent(){
    return lng("site_index");
}

function checkIfSelfUri($uri) {
    $decodedUri = strtolower(urldecode(html_entity_decode($uri)));
    $parsedUrl = parse_url($decodedUri);
    if ($parsedUrl === false || !isset($parsedUrl['host'])) {
        $decodedUriWithScheme = 'http://' . ltrim($decodedUri, '/');
        $parsedUrl = parse_url($decodedUriWithScheme);
    }
    if ($parsedUrl === false || !isset($parsedUrl['host'])) 
        $uri=""; // Or handle the error as per your requirements
    $host = strtolower($parsedUrl['host']);
    if (substr($host, 0, 4) === 'www.') 
        $host = substr($host, 4);
    $thisHost=parse_url(getenv("URI"))['host'];
    if ($host === $thisHost || (!empty($uri) && !filter_var($uri, FILTER_VALIDATE_URL)))
        $uri = "";
    return $uri; 
}

function getFavicon(){  
    $ret=file_get_contents("assets/logo_icon.png");
    return $ret;
}

function hexToRGB($hex) {
    $hex = ltrim($hex, '#');
    if (strlen($hex) == 3) {
        $hex = $hex[0].$hex[0] . $hex[1].$hex[1] . $hex[2].$hex[2];
    }
    return [
        'red'   => hexdec(substr($hex, 0, 2)),
        'green' => hexdec(substr($hex, 2, 2)),
        'blue'  => hexdec(substr($hex, 4, 2))
    ];
}

/**
 * Genera un'immagine per l'icona specificata.
 *
 * @param string $iconName  Nome dell'icona (es. "stella", "casa", "computer", "auto", "bici", "robot")
 * @param string|null $bgColor Colore di sfondo (in formato esadecimale con #, es. "#000000")
 * @param string|null $fgColor Colore in primo piano (in formato esadecimale con #)
 * @param int|null $rotation Angolo di rotazione (in gradi)
 */
function generateIconImage($iconName) {
    $seed = (int)(microtime(true) * 1000000);
    $width = 100;
    $height = 100;
    $im = imagecreatetruecolor($width, $height);
    $bgColor = isset($_GET['bg']) ? '#' . $_GET['bg'] : null;
    $fgColor = isset($_GET['fg']) ? '#' . $_GET['fg'] : null;
    $rotation = isset($_GET['rot']) ? intval($_GET['rot']) : null;
    $bgColors = ['#404040', '#109099', '#801020', '#203020', '#007000', '#700033'];
    $fgColors = ['#FFAAC0', '#FFFFFF', '#FBA5A3', '#CCA0FF', '#A8B5A3', '#D7FF70'];
    srand($seed);
    $randomNumber = mt_rand(0, 5);
    $bgColor = $bgColors[$randomNumber];
    do{
        $randomNumber = mt_rand(0, 5);
        $fgColor = $fgColors[$randomNumber];
        if ($fgColor!=$bgColor) break;
    } while (true);

    $randomNumber = mt_rand(-45, 45);
    $rotation = $randomNumber;
    $bgRGB = hexToRGB($bgColor);
    $fgRGB = hexToRGB($fgColor);
    $background = imagecolorallocate($im, $bgRGB['red'], $bgRGB['green'], $bgRGB['blue']);
    $foreground = imagecolorallocate($im, $fgRGB['red'], $fgRGB['green'], $fgRGB['blue']);
    imagefilledrectangle($im, 0, 0, $width, $height, $background);
    switch (strtolower($iconName)) {
        case '0star':
            $cx = $width / 2;
            $cy = $height / 2;
            $r_outer = 40;
            $r_inner = 20;
            $points = [];
            for ($i = 0; $i < 10; $i++) {
                $angle = deg2rad(-90 + $i * 36);
                $r = ($i % 2 == 0) ? $r_outer : $r_inner;
                $points[] = $cx + cos($angle) * $r;
                $points[] = $cy + sin($angle) * $r;
            }
            imagefilledpolygon($im, $points, 10, $foreground);
            break;
        case '0house':
            imagefilledrectangle($im, 20, 40, 80, 80, $foreground);
            $triangle = [20, 40, 80, 40, 50, 10];
            imagefilledpolygon($im, $triangle, 3, $foreground);
            imagefilledrectangle($im, 25, 50, 35, 60, $background);
            imagefilledrectangle($im, 65, 50, 75, 60, $background);
            imagefilledrectangle($im, 45, 60, 55, 80, $background);
            break;
        case '0computer':
            imagefilledrectangle($im, 20, 20, 80, 60, $foreground);
            imagefilledrectangle($im, 35, 65, 65, 75, $foreground);
            break;
        case '0car':
            $triangle = [25, 40, 65, 40, 45, 30];
            imagefilledpolygon($im, $triangle, 3, $foreground);
            imagefilledrectangle($im, 45, 25, 70, 40, $foreground);
            imagefilledrectangle($im, 20, 40, 80, 60, $foreground);
            imagefilledellipse($im, 35, 65, 15, 15, $foreground);
            imagefilledellipse($im, 65, 65, 15, 15, $foreground);
            break;
        case '0robot':
            imagefilledrectangle($im, 20, 20, 80, 80, $foreground);
            imagefilledellipse($im, 35, 40, 10, 10, $background);
            imagefilledellipse($im, 65, 40, 10, 10, $background);
            imagefilledrectangle($im, 35, 65, 65, 75, $background);
            break;
        case '0cloud':
            imagefilledellipse($im, 50, 50, 50, 30, $foreground);
            imagefilledellipse($im, 70, 40, 40, 25, $foreground);
            imagefilledellipse($im, 55, 30, 40, 25, $foreground);
            imagefilledrectangle($im, 30, 40, 80, 50, $foreground);
            break;
        case '0lock':
            imagefilledrectangle($im, 20, 40, 80, 80, $foreground);
            imagefilledarc($im, 50, 35, 40, 40, 180, 0, $foreground, IMG_ARC_PIE);
            imagefilledrectangle($im, 45, 60, 55, 70, $background);
            break;
        case '0rocket':
            $tipPoints = [
                50, 10,  // Vertice superiore (punta del razzo)
                30, 30,  // Angolo sinistro della base del triangolo
                70, 30   // Angolo destro della base del triangolo
            ];
            imagefilledpolygon($im, $tipPoints, 3, $foreground);
            imagefilledrectangle($im, 35, 30, 65, 70, $foreground);
            $leftFinPoints = [
                35, 70,  // Punto in cui il corpo incontra l'alette (sinistra)
                25, 80,  // Estremità sinistra dell'auretta
                35, 80   // Punto in basso a sinistra del corpo
            ];
            imagefilledpolygon($im, $leftFinPoints, 3, $foreground);
            $rightFinPoints = [
                65, 70,  // Punto in cui il corpo incontra l'auretta (destra)
                65, 80,  // Punto in basso a destra del corpo
                75, 80   // Estremità destra dell'auretta
            ];
            imagefilledpolygon($im, $rightFinPoints, 3, $foreground);
            imagefilledellipse($im, 50, 50, 10, 10, $background);
            break;
        case '0heart':
            imagefilledellipse($im, 35, 35, 30, 30, $foreground);
            imagefilledellipse($im, 65, 35, 30, 30, $foreground);
            $points = [20, 40, 75, 40, 55, 70];
            imagefilledpolygon($im, $points, 3, $foreground);
            break;
        case '0tree':
            $points_top = [
                20, 40,   // angolo sinistro della base
                80, 40,   // angolo destro della base
                50, 10    // vertice (punto più alto)
            ];
            imagefilledpolygon($im, $points_top, 3, $foreground);
            $points_bottom = [
                20, 70,   // angolo sinistro della base
                80, 70,   // angolo destro della base
                50, 20    // vertice (punto più alto del triangolo inferiore)
            ];
            imagefilledpolygon($im, $points_bottom, 3, $foreground);
            imagefilledrectangle($im, 45, 70, 55, 90, $foreground);
            break;
        case '0plane':
            $body = [
                50, 10,  // 1.  Naso (alto, al centro)
                53, 20,  // 2.  Lato destro del naso
                53, 30,  // 3.  Fuseliera destra (parte alta)
                85, 40,  // 4.  Punta ala destra
                70, 45,  // 5.  Bordo posteriore ala destra
                55, 50,  // 6.  Fuseliera destra (parte centrale)
                52, 55,  // 7.  Fianco destro vicino alla coda
                48, 55,  // 8.  Fianco sinistro vicino alla coda
                45, 50,  // 9.  Fuseliera sinistra (parte centrale)
                30, 45,  // 10. Bordo posteriore ala sinistra
                15, 40,  // 11. Punta ala sinistra
                47, 30,  // 12. Fuseliera sinistra (parte alta)
                47, 20   // 13. Lato sinistro del naso
            ];
            imagefilledpolygon($im, $body, count($body) / 2, $foreground);
            $tailStab = [
                40, 60,  // Estremo sinistro dello stabilizzatore
                65, 56,  // Estremo destro dello stabilizzatore
                60, 60,  // Bordo inferiore destro
                50, 65   // Bordo inferiore sinistro
            ];
            imagefilledpolygon($im, $tailStab, count($tailStab) / 2, $foreground);
            break;
        case '0eye':
            imagefilledellipse($im, 50, 50, 80, 40, $foreground);
            imagefilledellipse($im, 50, 50, 25, 25, $background);
            break;
        case '0envelope':
            imagefilledrectangle($im, 20, 30, 80, 70, $foreground);
            $flapPoints = [20, 30, 80, 30, 50, 50];
            imagefilledpolygon($im, $flapPoints, 3, $foreground);
            imagesetthickness($im, 5);
            imageline($im, 20, 30, 50, 50, $background);
            imageline($im, 80, 30, 50, 50, $background);
            break;
        default:
            // Se iconName non è riconosciuto, scrivi "N/D"
            imagestring($im, 5, 10, 40, "N/D", $foreground);
            break;
    }
    $im = imagerotate($im, $rotation, $background);
    ob_start();
    imagejpeg($im); // genera l'immagine JPEG e la manda in output
    $imageData = ob_get_clean();
    return base64_encode($imageData);
}

function generateRandomIcons() {
    // Array di icone disponibili
    $seed = (int)(microtime(true) * 1000000);
    $icons = array("0star", "0house", "0computer", "0car",  "0robot", "0cloud", "0lock", "0rocket", "0heart", "0tree", "0plane", "0envelope", "0eye");
    srand($seed);
    shuffle($icons);
    $images = array(generateIconImage($icons[0]), generateIconImage($icons[1]), generateIconImage($icons[2]),generateIconImage($icons[3]), generateIconImage($icons[4]), generateIconImage($icons[5]));
    $_SESSION["icons"] = $images;
    $_SESSION["iconNames"] = $icons;
    $randomNumber = mt_rand(0, 5);
    $_SESSION["_icon"] = $randomNumber;
    $_SESSION["_iconSelect"] = lng("0select").lng($icons[$randomNumber]);
    return $icons;
}
