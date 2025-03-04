<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024 - Aldo Prinzi
      Open source project - under MIT License
=====================================================================
This web app needs just Apache, PHP (7.4->8.3) and MySQL to work.
---------------------------------------------------------------------
This file contains the header part of the web app
v1.4.0 - Aldo Prinzi - 03 Mar 2025
-
v1.3.2 - Aldo Prinzi - 24 Feb 2025
- Added "user" link  
v1.3.1 - Aldo Prinzi - 13 Feb 2025
- Added "about" link under "short link" control and statistics 
v1.3.0 - Aldo Prinzi - 24 Jan 2025
- Added menu items
=====================================================================
*/

$userData="";
if (isset($_SESSION["user"]))
    $userData=$_SESSION["user"];
if (empty($userData))
    $userLink="href='/_pls_fnc_login' class='nav-item btn btn-warning btn-small'>Login";
else
    $userLink="href='/_pls_fnc_user' class='nav-item btn btn-secondary btn-small'><strong>".trim($userData["descr"])."</strong>";

?><html>
    <head>
        <title><?php echo $_SESSION["pageTitle"]; ?></title>
        <link rel="stylesheet" type="text/css" href="/html/site.css">
        <script src="/html/site.js"></script>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    </head>
    <body>
        <div>
            <table class="header" border=0 width="100%"><tr>
                <td width="40%" align="right" style="padding-right:10px"><img src="/html/logo_front.png"></td>
                <td width="50%" align="left"><h2><?php echo $_SESSION["pageTitle"]; ?></h2></td>
                <td><?php echo lng("language");?>:<?php echo $_SESSION["langButtons"]; ?></td>
            </tr>
            <tr>
                <td colspan="2" align="left" width="80%" style="padding-top:5px">
                    <nav class="nav-row"><a class="nav-item" href="/">Home</a>&nbsp;|&nbsp;<a class="nav-item" href="/pls_about" target='_blank'>About</a></nav>
                </td>
                <td style="padding-right:20px"><a <?php echo $userLink; ?></a></td>
            </tr>
        </table>
        </div>
