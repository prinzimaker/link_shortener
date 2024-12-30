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
v1.1.0 - Aldo Prinzi - 23 Dic 2024
=====================================================================
*/
?><html>
    <head>
        <title><?php echo $_SESSION["pageTitle"]; ?></title>
        <link rel="stylesheet" type="text/css" href="/html/site.css">
    </head>
    <body>
        <div class="header">
            <h1><?php 
            echo $_SESSION["pageTitle"]; 
            ?></h1>
        </div>
