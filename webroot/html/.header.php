<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024 - Aldo Prinzi
      Open source project - under MIT License
=====================================================================
This web app needs just Apache, PHP (74->8.3) and MySQL to work.
---------------------------------------------------------------------
This file contains the header part of the web app
-
v1.3.0 - Aldo Prinzi - 24 Jan 2025
- Added menu items
v1.3.1 - Aldo Prinzi - 13 Feb 2025
- Added "about" link under "short link" control and statistics 
=====================================================================
*/
?><html>
    <head>
        <title><?php echo $_SESSION["pageTitle"]; ?></title>
        <link rel="stylesheet" type="text/css" href="/html/site.css">
    </head>
    <body>
        <div>
            <table class="header" width="100%"><tr><td width="90%"><h1><?php echo $_SESSION["pageTitle"]; ?></h1></td>
            <td><?php echo lng("language");?>:<?php echo $_SESSION["langButtons"]; ?></td></tr>
            <tr><td align="left"><nav class="nav-row"><a class="nav-item" href="/">Home</a>&nbsp;|&nbsp;<a class="nav-item" href="https://flu.lu/about" target='_blank'>About</a>&nbsp;|&nbsp;<a class="nav-item" href="/login">Login</a></nav></td><td>&nbsp;</td></tr>
        </table>
        </div>
