<?php
/* ==================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License     
=====================================================================
This file contains the string labels for German language
===================================================================*/
function lng($key){
    switch ($key){
        case "": return "";
        case "user": return "Benutzer-ID";
        case "send": return "Senden";
        case "password": return "Passwort";
        case "login": return "Anmelden";
        case "register": return "Registrieren";
        case "ripeti_password": return "Passwort wiederholen";
        case "email": return "E-Mail-Adresse";
        case "verify": return "&Uuml;berpr&uuml;fen";
        case "autentication": return "Authentifizierung";
        case "forgot_pass": return "Ich habe mein Passwort vergessen";
        case "invalid_uid_or_pass": return "Ung&uuml;ltige Benutzer-ID oder Passwort";
        case "api_loop": return "Um Schleifen zu vermeiden, ist es nicht m&ouml;glich, einen ";
        case "api_invalid-short": return "Ung&uuml;ltige SHORT_ID angegeben.";
        case "front_insert-long": return "Geben Sie die lange URL ein, die gek&uuml;rzt werden soll...";
        case "front_shorten": return "Link k&uuml;rzen";
        case "front_information":
        case "information": return "Informationen";
        case "front_reduced-link": return "Gek&uuml;rzter Link";
        case "front_link-to-shrink": return "Zu k&uuml;rzender Link";
        case "error": return "Fehler";
        case "date": return "Datum";
        case "copy": return "kopieren";
        case "times": return "Mal";
        case "close": return "Schlie&szlig;en";
        case "language": return "Sprache";
        case "new apikey": return "Neuer API-Schl&uuml;ssel";
        case "change password": return "Passwort &auml;ndern";
        case "update": return "Aktualisieren";
        case "change_pass_form": return "Passwort &auml;ndern";
        case "repeat_password": return "Passwort wiederholen";
        case "ip-address": return "IP-Adresse";
        case "geoloc": return "Geolokalisierung";
        case "not-found": return "nicht gefunden";
        case "unavailable_data": return "Daten nicht verf&uuml;gbar";
        case "front_link-is": return "Der urspr&uuml;ngliche Link ist";
        case "front_copied-link": return "Link kopiert";
        case "front_was-req": return "Und wurde angefordert";
        case "front_link-created-on": return "Wurde erstellt am";
        case "front_short-link-is": return "Der kurze Link ist";
        case "front_copy-error": return "Fehler beim Kopieren des Links";
        case "front_insert-correct": return "Geben Sie einen korrekten Link in das Eingabefeld ein, bevor Sie auf die Schaltfl&auml;che &laquo; <strong>".lng("front_shorten")."</strong> &raquo; klicken.";
        case "front_downloads-info": return "Download-Informationen";
        case "change_link_code": return "Link-Code &auml;ndern";
        case "change": return "&Auml;ndern";
        case "code_exists": return "Dieser Kurzcode existiert bereits!";
        case "database_generic_error": return "Fehler bei der Registrierung in der Datenbank";
        case "front_incorrect-link": return "Ung&uuml;ltige <strong>URI</strong> oder Schleifen-<strong>URI</strong> (es ist nicht m&ouml;glich, einen <strong>".getenv("URI")."</strong>-Link zu k&uuml;rzen)";
        case "front_instr-small": return "Geben Sie den gek&uuml;rzten Link ein und dr&uuml;cken Sie die &laquo; <strong>Informationen</strong> &raquo;-Schaltfl&auml;che, um Details &uuml;ber den gek&uuml;rzten Link zu erhalten.";
        case "front_instructions": return '<p><strong>Dies ist eine Website zum Erstellen kurzer Links.</strong></p>
            <p>Das bedeutet, dass Sie mir einen langen Link geben, und ich gebe Ihnen einen kurzen Link zur&uuml;ck, der den urspr&uuml;nglichen ersetzen kann.</p>
            <h3>Wie funktioniert es?</h3>
            <p class="pad">Um einen kurzen Link zu erstellen, geben Sie einfach den langen Link in das Textfeld ein und dr&uuml;cken Sie die Schaltfl&auml;che &laquo; <strong>Link k&uuml;rzen</strong> &raquo;. Die gek&uuml;rzte Version wird in dem angezeigten Feld erscheinen.<br>
            Um den kurzen Link zu verwenden, kopieren Sie ihn und f&uuml;gen Sie ihn in Ihren Browser ein, und der Benutzer wird automatisch zum urspr&uuml;nglichen Link weitergeleitet.<br>
            Um die Statistiken anzuzeigen, dr&uuml;cken Sie die &laquo; <strong>'.lng("front_information").'</strong> &raquo;-Schaltfl&auml;che.</p>
            <h3>Beispiel</h3>
            <p class="pad">Wenn Sie einen kurzen Link f&uuml;r <a href="https://www.google.com/search?client=firefox-b-d&q=come+si+accorciano+i+link%3F">https://www.google.com/search?client=firefox-b-d&q=come+si+accorciano+i+link%3F</a> erstellen m&ouml;chten, 
            geben Sie ihn in das Textfeld ein und dr&uuml;cken Sie die &laquo; K&uuml;rzen &raquo;-Schaltfl&auml;che. Sobald Sie den kurzen Link haben, verwenden Sie ihn direkt in Ihrem Browser, um das Ergebnis zu sehen.</p>';
        case "front_qr-code": return "QR-Code";
        case "front_generate-qr": return "QR-Code generieren";
        case "front_download-qr": return "QR-Code herunterladen";
        case "front_stats": return "Statistiken";
        case "front_visits": return "Besuche";
        case "front_unique-visits": return "Einmalige Besuche";
        case "front_last-visit": return "Letzter Besuch";
        case "front_created-on": return "Erstellt am";
        case "front_location": return "Standort";
        case "front_no-data": return "Keine Daten verf&uuml;gbar";
        case "front_api-docs": return "API-Dokumentation";
        case "front_openapi": return "OpenAPI-Dokumentation";
        case "front_redoc": return "Redoc-Dokumentation";
        case "site_index": return '
            <header class="bigtitle">
                <h1>Kürzen, Teilen, Verfolgen!</h1>
                <p class="bigsubtitle">Ein Open-Source-Projekt zur Link-Verwaltung</p>
            </header>
            <div class="container">
                <main>
                    <h2>Warum ist es n&uuml;tzlich und warum k&uuml;rzen viele ihre Links?</h2>
                    <p>Ein k&uuml;rzerer Link ist einfacher zu teilen und optisch sauberer. Viele Dienste wie soziale Medien und Messaging-Apps beschr&auml;nken die Anzahl der Zeichen in Beitr&auml;gen oder Nachrichten, sodass lange URLs unhandlich und schwer lesbar werden k&ouml;nnen.</p>
                    <p>Ein weiterer Vorteil ist die dynamische Link-Verwaltung: Mit einem K&uuml;rzungsdienst kannst du das Ziel deines Links auch nach dem Teilen &auml;ndern, ohne alle Stellen, an denen er ver&ouml;ffentlicht wurde, aktualisieren zu m&uuml;ssen.</p>
                    <p>Schlie&szlig;lich kannst du die Klicks verfolgen, den Traffic analysieren, herausfinden, woher die Nutzer kommen, und deine Teilen-Strategie optimieren.</p>
                    <p>Unser Dienst ist nicht auf manuelle Nutzung beschr&auml;nkt: Dank offener APIs k&ouml;nnen Entwickler und Unternehmen die Link-Generierung und -Verwaltung in ihre eigenen Systeme integrieren.</p>                    
                    <div class="form-group center-content">
                        <a href="/_this_prj_login" class="btn btn-primary">Anmelden</a>
                        <a href="/_this_prj_register" class="btn btn-secondary">Registrieren</a>
                    </div>

                    <h2>Wie funktioniert es?</h2>
                    <div style="padding-left:30px;">
                        <h3>&Uuml;ber das Web</h3>
                        <ul class="list">
                            <li>🔒 Um unseren Link-Verk&uuml;rzungsdienst nutzen zu k&ouml;nnen, m&uuml;ssen Sie ein registrierter Benutzer sein.</li>
                            <li>1️⃣ F&uuml;gen Sie auf Ihrer Hauptseite Ihren langen Link in das obere Feld <strong>ein</strong>.</li>
                            <li>2️⃣ <strong>Klicken Sie auf "Verk&uuml;rzen"</strong> und Sie erhalten eine kurze URL sowie einen entsprechenden QR-Code.<div style="padding-left:20px">- Es wird ein zuf&auml;lliger kurzer Link generiert, aber <strong>Sie k&ouml;nnen ihn &auml;ndern</strong> und einen einpr&auml;gsameren verwenden.</div></li>
                            <li>3️⃣ <strong>Teilen Sie ihn</strong> &uuml;berall: soziale Medien, E-Mail, Nachrichten.</li>
                            <li>4️⃣ <strong>&Uuml;berwachen Sie</strong> die Besuche mit fortschrittlichen Statistiken, die Informationen wie Datum, Uhrzeit und geografische Position des Benutzers, der geklickt hat, liefern.</li>
                            <li>💡 <strong>Unterst&uuml;tzt QR-Codes</strong> f&uuml;r sofortiges Teilen!</li>
                        </ul>
                        <h3>&Uuml;ber API</h3>
                        <div style="padding-left:30px;">
                            💻 <strong>Funktioniert &uuml;ber API</strong>: Integrieren Sie unseren Dienst in Ihre Projekte mit unseren leistungsstarken und flexiblen APIs.
                            <br>Siehe: ><a class="nav-item" style="color:#A33" href="/pls_swagu" target="_blank">OpenAPI-Dokumentation</a> - oder: ><a class="nav-item" style="color:#A33" href="/pls_redoc" target="_blank">Redoc-API-Dokumentation</a>
                        </div>
                    </div>
                </main>
                <section>
                    <h2>Warum dieses Projekt w&auml;hlen?</h2>
                    <ul class="list">
                        <li>💻 <strong>Open Source und Kostenlos</strong> - Der Code ist f&uuml;r alle Nutzer verf&uuml;gbar.&nbsp;&gt;<a class="nav-item" style="color:#A33" href="/pls_about" target="_blank">GitHub</a></li>
                        <li>🔍 <strong>Transparent und Sicher</strong> - Kein verstecktes Tracking, keine invasiven Praktiken, kreativ und anders geschrieben, um Hacking-Risiken zu reduzieren.</li>
                        <li>🛠 <strong>Anpassbar</strong> - Modifizierbar, um deinen Bed&uuml;rfnissen gerecht zu werden.</li>
                        <li>👥 <strong>Von der Community Unterst&uuml;tzt</strong> - Erhalte Unterst&uuml;tzung und trage mit Verbesserungen bei.</li>
                        <li>📡 <strong>Offene APIs</strong> - Ideal f&uuml;r Entwickler und Unternehmen.</li>
                        <li>🚀 <strong>Unabh&auml;ngig</strong> - Keine Werbung, kein Unternehmenseinfluss, nur offene Technologie.</li>
                        <li>🌍 <strong>Europ&auml;isches Projekt</strong> - Unterst&uuml;tzt in den vier Hauptsprachen: Englisch, Italienisch, Franz&ouml;sisch und Deutsch. Du kannst die Sprache oben rechts im Header einfach ausw&auml;hlen.</li>
                    </ul>
                </section>
                <section><br>
                    <h2>H&auml;ufig Gestellte Fragen</h2>
                    <div class="accordion">
                        <div class="tab">
                            <input type="checkbox" id="faq1">
                            <label class="tab__label" for="faq1">Warum sollte ich einen Link k&uuml;rzen?</label>
                            <div class="tab__content">
                                <p>Das K&uuml;rzen von Links macht sie einfacher zu teilen, verbessert die Lesbarkeit und erm&ouml;glicht es dir, ihre Leistung zu verfolgen, um zu verstehen, woher dein Traffic kommt.</p>
                            </div>
                        </div>
                        <div class="tab">
                            <input type="checkbox" id="faq2">
                            <label class="tab__label" for="faq2">Brauche ich ein Konto, um den Dienst zu nutzen?</label>
                            <div class="tab__content">
                                <p>Ja, du musst ein registrierter Nutzer sein, um kurze Links zu erstellen. Das gew&auml;hrleistet Datenschutz, Sicherheit und sichert dir einen <strong>reservierten Zugang</strong> zu deinen Statistiken.</p>
                            </div>
                        </div>
                        <div class="tab">
                            <input type="checkbox" id="faq3">
                            <label class="tab__label" for="faq3">Verfallen meine Links?</label>
                            <div class="tab__content">
                                <p>Die Links bleiben unbegrenzt aktiv, es sei denn, du entscheidest dich, sie in deinen Kontoeinstellungen zu l&ouml;schen, was dir volle Kontrolle &uuml;ber ihre Lebensdauer gibt.</p>
                            </div>
                        </div>
                        <div class="tab">
                            <input type="checkbox" id="faq4">
                            <label class="tab__label" for="faq4">Wie viel kostet der Dienst?</label>
                            <div class="tab__content">
                                <p>Dieses Projekt ist Open Source – geh auf GitHub, lade es herunter und nutze es. Wenn du die Funktionen nutzen m&ouml;chtest, ohne es herunterzuladen und auf deinem eigenen Server zu installieren, folge den Anweisungen dessen, der es bereitgestellt hat.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        ';
        default: return "unbekanntes Sprachlabel $key...";
    }
}

function getLangDate($theDate){
    $date = new DateTime($theDate);
    $locale = 'de_DE';
    $formatter = new \IntlDateFormatter(
        $locale,                         // Locale
        \IntlDateFormatter::FULL,        // Typ des Datumsformats
        \IntlDateFormatter::FULL,        // Typ des Zeitformats
        'Europe/Berlin',                 // Zeitzone
        \IntlDateFormatter::GREGORIAN,   // Kalender
        "EEEE, dd. MMMM yyyy ! HH:mm:ss" // Formatierungsmuster
    );
    return str_replace("!", "um", $formatter->format($date));
}