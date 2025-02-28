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
-
Updated to
v1.4.0 - Aldo Prinzi - 03 Mar 2025

v1.3.2 - Aldo Prinzi - 24 Feb 2025 
       - Solved a bug that will shrink a local link already shrinked.
       - Added user link datatable view
       - Added user link delete
v1.3.0 - Aldo Prinzi - 25 Jan 2025
=====================================================================
*/
// form per lo shorten del link
function getShortenContent($uri){
    return '<form action="_this_prj_shorten" method="post">
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
    $ret='<div class="alert alert-warning"><form action="_this_prj_changecode"  method="post">
    <input type="hidden" name="shortcode" value="'.$code.'">
    <table><tr>
    <td><label>'.lng("front_reduced-link").':<strong>'.getenv("URI").$uri.'</strong></label><td>&nbsp;-&nbsp;</td><td><label>'.lng("change_link_code").'</label></td>
    <td><input type="text" class="input-text" name="newcode" placeholder="" value="'.$code.'"></td>
    <td>&nbsp;</td><td><button type="submit" class="btn btn-primary">'.lng("change").'</button></td>
    </tr></table>
</form></div>';
    return $ret;
}

function getLoginForm($userid=""){
    isset($_SESSION["loginerr"])?$errMsg=lng($_SESSION["loginerr"]):"";
    $_SESSION["loginerr"]="";
    $ret='<div class="auth-div"><div class="login-header"><h3>'.lng("autentication").'</h3></div>
    <form class="auth-form" action="_this_prj_login" method="post">
        <div class="form-group">
            <label for="userid">'.lng("user").'</label>
            <input id="userid" type="text" class="input-text2" name="userid" placeholder="'.lng("user").'" value="'.$userid.'">
            <label for="password">'.lng("password").'</label>
            <input type="password" class="input-text2" name="password" placeholder="'.lng("password").'" value="">
            <br><a class="forgotpass" href="/_this_prj_fgtpass">'.lng("forgot_pass").'</a></div>
        <button type="submit" class="btn btn-primary">'.lng("login").'</button>
    </form>
    <div class="err-message">'.$errMsg.'</div>
</div><br>';
    return $ret;
}

function getForgotPasswordForm($user){
    $ret="<div class='auth-div'><div class='login-header'><h3>".lng("change_pass_form")."</h3></div>
    <form class='auth-form' action='_this_prj_forgotpass' method='post'>
        <div class='form-group'>
            <label for='userid'>".lng("user")."</label>
            <input id='userid' type='text' class='input-text2' name='userid' placeholder='".lng("user")."' value=''>
            <label for='pass1'>".lng("password")."</label>
            <input id='pass1' type='password' class='input-text2' name='password1' value=''>
            <label for='pass2'>".lng("repeat_password")."</label>
            <input id='passe' type='password' class='input-text2' name='password2' value=''>
        </div>
        <button type='submit' class='btn btn-primary'>".lng("send")."</button>
    </form></div>";
    return $ret;
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
            <div class="modal-header">
                TITLE
                <span class="modal-closer" onclick="closemodal()">&times;</span>
            </div>
            <div class="modal-content">
                BODY<br>
                BUDY
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">OK</button>
            </div>
        </div>
        
        <div class="alert alert-info">
            <table width="100%"><tr><td width="50%">
                <label for="userid">'.lng("user").'</label><br>
                <input style="margin-top:3px" id="userid" type="text" class="input-text2" name="userid" placeholder="'.lng("user").'" value="'.$userData["descr"].'">
                </td><td>&nbsp;</td><td>
                <label for="userid">API key</label><br>
                <input style="margin-top:3px" id="userid" type="text" class="input-text2" name="userid" placeholder="API key" value="'.$userData["apikey"].'">
                </td></tr>
                <tr><td><label for="userid">'.lng("email").'</label><br>
                    <input style="margin-top:3px" id="userid" type="text" class="input-text2" name="userid" placeholder="'.lng("user").'" value="'.$userData["email"].'">
                </td><td>&nbsp;</td><td>
                    <table><tr><td>
                    <button type="button" class="btn btn-warning" onclick="openmodal()" o-n-click=\'window.location.href="/_this_prj_fgtpass"\'>'.lng("change password").'</button>&nbsp;
                    </td><td>
                    <form method="post" action="_this_prj_newapikey"><input type="submit" class="btn btn-primary" value="'.lng("new apikey").'"></form>
                    </td><td>
                    &nbsp;<button type="button" class="btn btn-secondary" onclick=\'window.location.href="/_this_prj_logout"\'>Logout</button>
                    </td></tr></table>
                </td></tr>
            </table>
        </div>
        <div class="form-group">
        <label>User\'s Links</label>
        <div class="userTabLinks">
        <table id="userCodesTable" class="display"><thead>
        <tr><th>short_id</th><th>&nbsp;</th><th>Uri</th><th>Calls</th><th>Created</th><th>Last call</th></tr>
        </thead><tbody>
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
        $content.= '<td><a href="/_this_prj_shortinfo?code='. $row['short_id'] .'">' . $row['short_id'] . '</a></td>';
        $content.= '<td><a href="/_this_prj_removeshortinfo?code='. $row['short_id'] .'"><button class="btn btn-small btn-warning">DEL</button></a></td>';
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
    $ret=file_get_contents("html/logo_icon.png");
    return $ret;
}