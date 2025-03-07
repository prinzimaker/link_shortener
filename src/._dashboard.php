<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License     
=====================================================================
This web app needs just Apache, PHP (7.4->8.3) and MySQL to work.
---------------------------------------------------------------------
This file contains all the dashboard html page/form generators 
=====================================================================
*/
// form per lo shorten del link

function getDashboard(){
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
                <div class="modal-header"><span id="modalTitle">TITOLO</span><span class="modal-closer" onclick="closemodal()">&times;</span>
                </div>
                <div class="modal-content">
                    <div class="form-group"> <label for="password">Label 1</label>
                        <input id="modalData1" style="width:95%" type="text" class="input-text" name="input1" value="">
                    </div>
                    <div class="form-group"> <label for="password_confirm">Label 2</label>
                        <input id="modalData2" style="width:95%" type="text" class="input-text" name="input2" value="">
                    </div> 
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Change Password">
                </div>
            </form> 
        </div>
        <div class="form-group"><label>Customer\'s List</label><div class="userTabLinks">
            <table id="userCodesTable" class="display"><thead><tr><th rowspan=2>Name</th><th rowspan=2>E-mail</th><th rowspan=2>Status</th><th colspan=2>Links</th></tr><tr><th>Used</th><th>Max</th></tr></thead><tbody>
    ';
    
    $db = new Database();
    $result = $db->getCustomersData();

    // Costruzione della tabella HTML
    foreach ($result as $row) {
        if ($row['adm']!=0)
            $actbtn="&nbsp;";
        else {
            $actbtn="<button class='btn btn-small ";
            if ($row['verified']!=0){
                if ($row['active']!=0)
                    $actbtn.="btn-secondary' onclick='openmodal(\"Deactivate\",".$row["id"].",\"".$row['descr']."\")'>DEAC";
                else
                    $actbtn.="btn-warning' onclick='openmodal(\"Activate\",".$row["id"].",\"".$row['descr']."\")'>ACTV";
            } else {
                $actbtn.="btn-primary' onclick='openmodal(\"Delete\",".$row["id"].",\"".$row['descr']."\")'>DEL";
            }
            $actbtn.="</button>";
        }
        $content.="<tr>
        <td>" . $row['descr'] . "</td>
        <td>" . $row['email'] . "</td>
        <td>" . $actbtn ."</td>
        <td align='right'>" . $row["used_links"]."</td><td align='right'>";
        if ($row['adm']!=0)
            $content.="&nbsp;";
        else
            $content.= $row['max_links'];
        $content.="</td></tr>";
    }
    return $content.'</tbody></table></div></div>';
}


