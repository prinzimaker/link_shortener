<?php
/* ==================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License     
=====================================================================
This file contains the string labels for english language
===================================================================*/
function lng($key){
    switch ($key){
        case "":return "";
        case "user": return "User-id";
        case "password": return "Password";
        case "login": return "Login";
        case "register": return "Registrer";
        case "ripeti_password": return "Ripeat password";
        case "email": return "E-mail address";
        case "verify": return "Verify";
        case "autentication": return "Autentication";
        case "forgot_pass": return "I forgot my password";
        case "invalid_uid_or_pass": return "Invalid user-id or password";
        case "api_loop": return "To avoid loops, it isn't possible to shorten a ";
        case "api_invalid-short": return "Invalid SHORT_ID provided.";
        case "front_insert-long": return "Insert the long URL to shorten...";
        case "front_shorten": return "Shorten the link";
        case "front_information":
        case "information":return "Information";
        case "front_reduced-link": return "Reduced link";
        case "front_link-to-shrink": return "Link to shrink";
        case "error": return "Error";
        case "date": return "date";
        case "copy": return "copy";
        case "times": return "times";
        case "close": return "Close";        
        case "language":return "Lingua";
        case "new apikey":return "New API key";
        case "change password":return "Change password";
        case "update":return "Aggiorna";
        case "ip-address": return "ip address";
        case "geoloc": return "geolocalisation";
        case "not-found": return "not found";
        case "unavailable_data": return "Unavailable data";
        case "front_link-is": return "The original link is";
        case "front_copied-link":return "Link copied";
        case "front_was-req":return "And was requested";
        case "front_link-created-on": return "Was created on";
        case "front_short-link-is": return "The short link is";
        case "front_copy-error":return "Link copy error";
        case "front_insert-correct":return "Insert a correct link in the input box before hit the &quot;<strong>".lng("front_shorten")."</strong>&quot; button.";
        case "front_downloads-info": return "Download&apos;s info";
        case "change_link_code": return "Change link code";
        case "change": return "Change";
        case "code_exists":"This short-code already exists!";
        case "database_generic_error": return "Error during database registration";
        case "front_incorrect-link":return "Incorrect <strong>uri</strong> or loop-<strong>uri</strong> (isn't possible to shrink a <strong>".getenv("URI")."</strong> link)";
        case "front_instr-small": return "Enter the shortened link and press the <strong>Information</strong> button to get details about the shortened link.";
        case "front_instructions": return '<p><strong>This is a website for creating short links.</strong></p>
            <p>It means that you provide me with a long link, and I return a short link that can replace the original one.</p>
            <h3>How does it work?</h3>
            <p class="pad">To create a short link, simply enter the long link in the text field and press the "<strong>Shorten the link</strong>" button. The shortened version will be displayed in the box that appears.<br>
            To use the short link, just copy and paste it into your browser, and the user will be automatically redirected to the original link.<br>
            To view the statistics, press the "<strong>'.lng("front_information").'</strong>" button.</p>
            <h3>Example</h3>
            <p class="pad">If you want to create a short link for <a href="https://www.google.com/search?client=firefox-b-d&q=come+si+accorciano+i+link%3F">https://www.google.com/search?client=firefox-b-d&q=come+si+accorciano+i+link%3F</a>, 
            enter it into the text field and press the "Shorten" button. Once you have the short link, use it directly in your browser to see the result.</p>';
        default: return "unknown $key language label...";
    }
}

function getLangDate($theDate){
    $date = new DateTime($theDate);
    $locale = 'en_EN';
    $formatter = new \IntlDateFormatter(
        $locale,                         // Locale
        \IntlDateFormatter::FULL,         // Tipo di formattazione della data
        \IntlDateFormatter::FULL,         // Tipo di formattazione dell'ora
        'Europe/Rome',                   // Fuso orario
        \IntlDateFormatter::GREGORIAN,    // Calendario
        "EEEE MMMM dd, yyyy ! HH:mm:ss"      // Pattern di formattazione
    );
    return str_replace("!","at",$formatter->format($date));
}