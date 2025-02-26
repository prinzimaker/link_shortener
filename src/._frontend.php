<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License     
=====================================================================
This web app needs just Apache, PHP (74->8.3) and MySQL to work.
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
        </div>
        <div class="forgotpass" onclick="forgotPass()">'.lng("forgot_pass").'</div>
        <button type="submit" class="btn btn-primary">'.lng("login").'</button>
    </form>
    <div class="err-message">'.$errMsg.'</div>
</div><br>
<script>
    function forgotPass(){
        $uid=document.getElementById("userid").value;
        window.location.href="/_this_prj_fgtpass?uid="+$uid;
    }
</script>';
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
                    <button type="button" class="btn btn-warning" onclick=\'window.location.href="/_this_prj_fgtpass"\'>'.lng("change password").'</button>&nbsp;
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
    $ret="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAdCAMAAAGLR64jAAAKN2lDQ1BzUkdCIElFQzYxOTY2LTIuMQAAeJydlndUU9kWh8+9N71QkhCKlNBraFICSA29SJEuKjEJEErAkAAiNkRUcERRkaYIMijggKNDkbEiioUBUbHrBBlE1HFwFBuWSWStGd+8ee/Nm98f935rn73P3Wfvfda6AJD8gwXCTFgJgAyhWBTh58WIjYtnYAcBDPAAA2wA4HCzs0IW+EYCmQJ82IxsmRP4F726DiD5+yrTP4zBAP+flLlZIjEAUJiM5/L42VwZF8k4PVecJbdPyZi2NE3OMErOIlmCMlaTc/IsW3z2mWUPOfMyhDwZy3PO4mXw5Nwn4405Er6MkWAZF+cI+LkyviZjg3RJhkDGb+SxGXxONgAoktwu5nNTZGwtY5IoMoIt43kA4EjJX/DSL1jMzxPLD8XOzFouEiSniBkmXFOGjZMTi+HPz03ni8XMMA43jSPiMdiZGVkc4XIAZs/8WRR5bRmyIjvYODk4MG0tbb4o1H9d/JuS93aWXoR/7hlEH/jD9ld+mQ0AsKZltdn6h21pFQBd6wFQu/2HzWAvAIqyvnUOfXEeunxeUsTiLGcrq9zcXEsBn2spL+jv+p8Of0NffM9Svt3v5WF485M4knQxQ143bmZ6pkTEyM7icPkM5p+H+B8H/nUeFhH8JL6IL5RFRMumTCBMlrVbyBOIBZlChkD4n5r4D8P+pNm5lona+BHQllgCpSEaQH4eACgqESAJe2Qr0O99C8ZHA/nNi9GZmJ37z4L+fVe4TP7IFiR/jmNHRDK4ElHO7Jr8WgI0IABFQAPqQBvoAxPABLbAEbgAD+ADAkEoiARxYDHgghSQAUQgFxSAtaAYlIKtYCeoBnWgETSDNnAYdIFj4DQ4By6By2AE3AFSMA6egCnwCsxAEISFyBAVUod0IEPIHLKFWJAb5AMFQxFQHJQIJUNCSAIVQOugUqgcqobqoWboW+godBq6AA1Dt6BRaBL6FXoHIzAJpsFasBFsBbNgTzgIjoQXwcnwMjgfLoK3wJVwA3wQ7oRPw5fgEVgKP4GnEYAQETqiizARFsJGQpF4JAkRIauQEqQCaUD";
    $ret.="akB6kH7mKSJGnyFsUBkVFMVBMlAvKHxWF4qKWoVahNqOqUQdQnag+1FXUKGoK9RFNRmuizdHO6AB0LDoZnYsuRlegm9Ad6LPoEfQ4+hUGg6FjjDGOGH9MHCYVswKzGbMb0445hRnGjGGmsVisOtYc64oNxXKwYmwxtgp7EHsSewU7jn2DI+J0cLY4X1w8TogrxFXgWnAncFdwE7gZvBLeEO+MD8Xz8MvxZfhGfA9+CD+OnyEoE4wJroRIQiphLaGS0EY4S7hLeEEkEvWITsRwooC4hlhJPEQ8TxwlviVRSGYkNimBJCFtIe0nnSLdIr0gk8lGZA9yPFlM3kJuJp8h3ye/UaAqWCoEKPAUVivUKHQqXFF4pohXNFT0VFysmK9YoXhEcUjxqRJeyUiJrcRRWqVUo3RU6YbStDJV2UY5VDlDebNyi/IF5UcULMWI4kPhUYoo+yhnKGNUhKpPZVO51HXURupZ6jgNQzOmBdBSaaW0b2iDtCkVioqdSrRKnkqNynEVKR2hG9ED6On0Mvph+nX6O1UtVU9Vvuom1TbVK6qv1eaoeajx1UrU2tVG1N6pM9R91NPUt6l3qd/TQGmYaYRr5Grs0Tir8XQObY7LHO6ckjmH59zWhDXNNCM0V2ju0xzQnNbS1vLTytKq0jqj9VSbru2hnaq9Q/uE9qQOVcdNR6CzQ+ekzmOGCsOTkc6oZPQxpnQ1df11Jbr1uoO6M3rGelF6hXrtevf0Cfos/ST9Hfq9+lMGOgYhBgUGrQa3DfGGLMMUw12G/YavjYyNYow2GHUZPTJWMw4wzjduNb5rQjZxN1lm0mByzRRjyjJNM91tetkMNrM3SzGrMRsyh80dzAXmu82HLdAWThZCiwaLG0wS05OZw2xljlrSLYMtCy27LJ9ZGVjFW22z6rf6aG1vnW7daH3HhmITaFNo02Pzq62ZLde2xvbaXPJc37mr53bPfW5nbse322N3055qH2K/wb7X/oODo4PIoc1h0tHAMdGx1vEGi8YKY21mnXdCO3k5rXY65vTW2cFZ7HzY+RcXpkuaS4vLo3nG8/jzGueNueq5clzrXaVuDLdEt71uUnddd457g/sDD30PnkeTx4SnqWeq50HPZ17WXiKvDq/XbGf2SvYpb8Tbz7vEe9CH4";
    $ret.="hPlU+1z31fPN9m31XfKz95vhd8pf7R/kP82/xsBWgHcgOaAqUDHwJWBfUGkoAVB1UEPgs2CRcE9IXBIYMj2kLvzDecL53eFgtCA0O2h98KMw5aFfR+OCQ8Lrwl/GGETURDRv4C6YMmClgWvIr0iyyLvRJlESaJ6oxWjE6Kbo1/HeMeUx0hjrWJXxl6K04gTxHXHY+Oj45vipxf6LNy5cDzBPqE44foi40V5iy4s1licvvj4EsUlnCVHEtGJMYktie85oZwGzvTSgKW1S6e4bO4u7hOeB28Hb5Lvyi/nTyS5JpUnPUp2Td6ePJninlKR8lTAFlQLnqf6p9alvk4LTduf9ik9Jr09A5eRmHFUSBGmCfsytTPzMoezzLOKs6TLnJftXDYlChI1ZUPZi7K7xTTZz9SAxESyXjKa45ZTk/MmNzr3SJ5ynjBvYLnZ8k3LJ/J9879egVrBXdFboFuwtmB0pefK+lXQqqWrelfrry5aPb7Gb82BtYS1aWt/KLQuLC98uS5mXU+RVtGaorH1futbixWKRcU3NrhsqNuI2ijYOLhp7qaqTR9LeCUXS61LK0";
    $ret.="rfb+ZuvviVzVeVX33akrRlsMyhbM9WzFbh1uvb3LcdKFcuzy8f2x6yvXMHY0fJjpc7l+y8UGFXUbeLsEuyS1oZXNldZVC1tep9dUr1SI1XTXutZu2m2te7ebuv7PHY01anVVda926vYO/Ner/6zgajhop9mH05+x42Rjf2f836urlJo6m06cN+4X7pgYgDfc2Ozc0tmi1lrXCrpHXyYMLBy994f9Pdxmyrb6e3lx4ChySHHn+b+O31w0GHe4+wjrR9Z/hdbQe1o6QT6lzeOdWV0iXtjusePhp4tLfHpafje8vv9x/TPVZzXOV42QnCiaITn07mn5w+lXXq6enk02O9S3rvnIk9c60vvG/wbNDZ8+d8z53p9+w/ed71/LELzheOXmRd7LrkcKlzwH6g4wf7HzoGHQY7hxyHui87Xe4Znjd84or7ldNXva+euxZw7dLI/JHh61HXb95IuCG9ybv56Fb6ree3c27P3FlzF3235J7SvYr7mvcbfjT9sV3qID0+6j068GDBgztj3LEnP2X/9H686CH5YcWEzkTzI9tHxyZ9Jy8/Xvh4/EnWk5mnxT8r/1z7zOTZd794/DIwFTs1/lz0/NOvm1+ov9j/0u5l73TY9P1XGa9mXpe8UX9z4C3rbf+7mHcTM7nvse8rP5h+6PkY9PHup4xPn34D94Tz+49wZioAAADDUExURer65eDz6f///9zx5tbv4eH32vD77N321dLt3sjp18Lm0vz++9b1zP3+/LThyLzkzqzewsTxtszywLftpqbbvYLNpP7+/pTUsOv48YvQqv///qjpkpvmgnPGmGvDkmbBj/L69FW6g5Lkdo3jcPn9+IfhaYribHbdU3veWn7fXXzeW2raRGzaR064fkOzdfv++juwbySmXhqjVxGfUQqcTA2eTgibSmbZP1/XNlPUKE/TI+X17EnSGzrOCTbNBDPMAAAAAPtjXe0AAAB";
    $ret.="BdFJOU[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[8AMFXsxwAAAAlwSFlzAAAuIwAALiMBeKU/dgAAAk1JREFUeJxtU21P2mAUPW0vFUERp/IidHOOsYUME0W+LPjE314/aOJL3EhwihKjLgUnc4O1RcA+e1paxISTNM099/Tec+9NabcGk2prcAjPygUV7hogeRkF2rGHnFAtgs6ejhjJO/BAyxiG9rDD6QHEK+CcmM7cjES7dx3xfmB0m824zDFlz9YQ6isaIX1S2UNeIhhbVyWobRKV14VGpQ2+54oZQWKjFruHmTQC8L9VutvG4NKPh8V4mjoZmGtu9L14UrpPLJCXOs5yawOlX2FRwwbix4oBnAIfEjCopCtfS0HR1m2ZwGpH5YBIpURb6LNRP441GEgvVqSxD/mwTNE4XpB+1ikmmkb8OK9kJVpA23GN8cc32K+gI4z1PYE07HVVdzjxfDmIfx4cANq8T/ye0/SZjRBw7RIcS2f5IbPcMqZLNBPqyumoiboNca+3XFpdHRup2rR4uKkEoXPwLHZpD9tq1WeY16V5w9iLe0HcTITwxk91u5lICK8xsH7OzzfFOnBRDIcxDTn0Vi7ypDs5b1/97mSSx9y5wzPfmlS0RJ7vF66DnT32IS/i3pTFZaSPETKWBWmozU/Bx9p5Z8up55QfrSQQMkZ3lnKXxpIvaG3iVOv078NSMJaHuWTbyta3rpKyZiGPMrfDmBDwxns5PqfEda0W0byBpSXTGQnSrvtVqxn7B9PEbaLV9vh3lpoSr16MqrOpkDtzgLFZb1stW/wQVbUgT92UU+vZ4joM+vn6DPrn5qvsUzRqmyww2a0r+MOmVfEF1/b0rMB/IHDOM4DGM/UAAAAASUVORK5CYII=";
    return str_replace("[","/",$ret);
}