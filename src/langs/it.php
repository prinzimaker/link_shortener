<?php
/* ==================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License     
=====================================================================
This file contains the string labels for italian language
===================================================================*/
function lng($key){
    switch ($key){
        case "":return "";
        case "user": return "Utente";
        case "password": return "Password";
        case "login": return "Accedi";
        case "register": return "Registrati";
        case "ripeti_password": return "Ripeti password";
        case "email": return "Indirizzo e-mail";
        case "verify": return "Verifica";
        case "update":return "Update";
        case "autentication": return "Autenticazione";
        case "forgot_pass": return "Ho dimenticato la mia password";
        case "invalid_uid_or_pass": return "Utente o password non validi";
        case "api_loop": return "Non è possibile creare un look per ridurre il link ";
        case "api_invalid-short": return "Questo SHORT_ID non è valido.";
        case "front_insert-long": return "Inserisci qui il link lungo...";
        case "front_shorten": return "Riduci il link";
        case "front_information":
        case "information":return "Informazioni";
        case "language":return "Language";
        case "unavailable_data": return "Informazioni non disponibili";
        case "front_reduced-link": return "Link ridotto";
        case "front_copied-link":return "Link copiato";
        case "new apikey":return "Nuova API key";
        case "change password":return "Cambia password";
        case "front_link-to-shrink": return "Link da ridurre";
        case "front_insert-correct":return "Inserire un link corretto nell'apposito spazio prima di premere &quot;<strong>".lng("front_shorten")."</strong>&quot;";
        case "error": return "Errore";
        case "front_copy-error":return "Errore nella copia";
        case "front_link-created-on": return "&Egrave; stato creato il";
        case "front_short-link-is": return "Il link corto &egrave;";
        case "front_downloads-info": return "Informazioni di download";
        case "not-found": return "non trovato";
        case "front_instr-small": return "Inserire il link ridotto e premere il tasto &quot;<strong>Informazioni</strong>&quot; per ottenere informazioni sul link ridotto.";
        case "front_incorrect-link":return "<strong>uri</strong> non corretto oppure loop-<strong>uri</strong> (non è possibile, ne consigliabile, accorciare un link di <strong>".getenv("URI")."</strong>)";
        case "date": return "data";
        case "copy": return "copia";
        case "close": return "Chiudi";
        case "times": return "volte";
        case "change_link_code": return "Cambia il codice del link:";
        case "change": return "Cambia";
        case "code_exists":"Questo codice esiste già!";
        case "database_generic_error": return "Errore durante la registrazione nel database";
        case "front_was-req":return "Ed &egrave; stato richiesto";
        case "geoloc": return "geolocalizazione";
        case "front_link-is": return "Il link originale &egrave;";
        case "front_instructions": return '<p><strong>Questo &egrave; un sito per la creazione di link corti.</strong></p>
                <p>Vuol dire che tu mi passi un link lungo e io ti restituisco un link corto che pu&ograve; essere sostituito al link originale.</p>
                <h3>Come funziona?</h3>
                <p class="pad">Per creare un link corto, basta inserire il link lungo nel campo di testo e premere il pulsante "<strong>Riduci il link</strong>", la versione accorciata sar&agrave; visualizzata nella casella che comparirà.<br>
                Per utilizzare il link corto, basta copiarlo e incollarlo nel browser e l&apos;utilizzatore verr&agrave; reindirizzato automaticamente verso il link originale.<br>
                Per visualizzare le statistiche premere il pulsante "<strong>'.lng("front_information").'</strong>".</p>
                <h3>Esempio</h3>
                <p class="pad">Se vuoi creare un link corto per <a href="https://www.google.com/search?client=firefox-b-d&q=come+si+accorciano+i+link%3F">https://www.google.com/search?client=firefox-b-d&q=come+si+accorciano+i+link%3F</a>, 
                inseriscilo nel campo di testo e premi il pulsante "Shorten", quando avrai il link corto usalo direttamente sul browser per visualizzare il risultato.</p>';
        default: return "valore label $key sconosciuto...";
    }
}

function getLangDate($theDate){
    $date = new DateTime($theDate);
    $locale = 'it_IT';
    $formatter = new \IntlDateFormatter(
        $locale,                         // Locale
        \IntlDateFormatter::FULL,         // Tipo di formattazione della data
        \IntlDateFormatter::FULL,         // Tipo di formattazione dell'ora
        'Europe/Rome',                   // Fuso orario
        \IntlDateFormatter::GREGORIAN,    // Calendario
        "EEEE dd MMMM yyyy ! HH:mm:ss"      // Pattern di formattazione
    );
    return str_replace("!","alle",$formatter->format($date));
}