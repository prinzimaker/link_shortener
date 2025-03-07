<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License
=====================================================================
This web app needs just Apache, PHP (7.4->8.3) and MySQL to work.
---------------------------------------------------------------------
This file contains the header part of the web app

=====================================================================
*/

// Initialize user data
$userData="";
// Check if user session exists
if (isset($_SESSION["user"]))
    $userData=$_SESSION["user"];
// Determine the user link based on session data
if (empty($userData) || !is_array($userData))
    $userLink="<a href='/_pls_fnc_login' class='nav-item btn btn-warning btn-small'>Login</a>";
else
    $userLink="<a href='/_pls_fnc_user' class='nav-item btn btn-secondary btn-small'><strong>".trim($userData["descr"])."</strong></a>";

?><html>
    <head>
        <!-- Set the page title dynamically -->
        <title><?php echo $_SESSION["pageTitle"]; ?></title>
        <!-- Link to the site's CSS file -->
        <link rel="stylesheet" type="text/css" href="/assets/site.css">
        <!-- Link to the site's JavaScript file -->
        <script src="/assets/site.js"></script>
        <!-- Link to Google Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    </head>
    <body>
        <div>
            <!-- Header table layout -->
            <table class="header" border=0 width="100%"><tr>
                <!-- Logo section -->
                <td width="38%" align="right" style="padding-right:10px"><img alt='logo' src="/assets/logo_front.png"></td>
                <!-- Page title section -->
                <td width="48%" align="left"><h2><?php echo $_SESSION["pageTitle"]; ?></h2></td>
                <!-- Language selection section -->
                <td><?php echo lng("language");?>:<?php echo $_SESSION["langButtons"]; ?></td>
            </tr>
            <tr>
                <!-- Navigation links -->
                <td colspan="2" align="left" width="80%" style="padding-top:5px">
                    <nav class="nav-row"><a class="nav-item" href="/">Home</a>&nbsp;|&nbsp;<a class="nav-item" href="/pls_about" target='_blank'>About</a></nav>
                </td>
                <!-- User link section -->
                <td style="padding-right:20px"><?php echo $userLink; ?></td>
            </tr>
        </table>
        </div>
    </body>
</html>
