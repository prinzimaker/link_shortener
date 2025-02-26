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
        case "password": return "Passwort";
        case "login": return "Anmelden";
        case "register": return "Registrieren";
        case "ripeti_password": return "Passwort wiederholen";
        case "email": return "E-Mail-Adresse";
        case "verify": return "Überprüfen";
        case "autentication": return "Authentifizierung";
        case "forgot_pass": return "Ich habe mein Passwort vergessen";
        case "invalid_uid_or_pass": return "Ungültige Benutzer-ID oder Passwort";
        case "api_loop": return "Um Schleifen zu vermeiden, ist es nicht möglich, einen ";
        case "api_invalid-short": return "Ungültige SHORT_ID angegeben.";
        case "front_insert-long": return "Geben Sie die lange URL ein, die gekürzt werden soll...";
        case "front_shorten": return "Link kürzen";
        case "front_information":
        case "information": return "Informationen";
        case "front_reduced-link": return "Gekürzter Link";
        case "front_link-to-shrink": return "Zu kürzender Link";
        case "error": return "Fehler";
        case "date": return "Datum";
        case "copy": return "kopieren";
        case "times": return "Mal";
        case "close": return "Schließen";
        case "language": return "Sprache";
        case "new apikey": return "Neuer API-Schlüssel";
        case "change password": return "Passwort ändern";
        case "update": return "Aktualisieren";
        case "ip-address": return "IP-Adresse";
        case "geoloc": return "Geolokalisierung";
        case "not-found": return "nicht gefunden";
        case "unavailable_data": return "Daten nicht verfügbar";
        case "front_link-is": return "Der ursprüngliche Link ist";
        case "front_copied-link": return "Link kopiert";
        case "front_was-req": return "Und wurde angefordert";
        case "front_link-created-on": return "Wurde erstellt am";
        case "front_short-link-is": return "Der kurze Link ist";
        case "front_copy-error": return "Fehler beim Kopieren des Links";
        case "front_insert-correct": return "Geben Sie einen korrekten Link in das Eingabefeld ein, bevor Sie auf die Schaltfläche « <strong>".lng("front_shorten")."</strong> » klicken.";
        case "front_downloads-info": return "Download-Informationen";
        case "change_link_code": return "Link-Code ändern";
        case "change": return "Ändern";
        case "code_exists": return "Dieser Kurzcode existiert bereits!";
        case "database_generic_error": return "Fehler bei der Registrierung in der Datenbank";
        case "front_incorrect-link": return "Ungültige <strong>URI</strong> oder Schleifen-<strong>URI</strong> (es ist nicht möglich, einen <strong>".getenv("URI")."</strong>-Link zu kürzen)";
        case "front_instr-small": return "Geben Sie den gekürzten Link ein und drücken Sie die « <strong>Informationen</strong> »-Schaltfläche, um Details über den gekürzten Link zu erhalten.";
        case "front_instructions": return '<p><strong>Dies ist eine Website zum Erstellen kurzer Links.</strong></p>
            <p>Das bedeutet, dass Sie mir einen langen Link geben, und ich gebe Ihnen einen kurzen Link zurück, der den ursprünglichen ersetzen kann.</p>
            <h3>Wie funktioniert es?</h3>
            <p class="pad">Um einen kurzen Link zu erstellen, geben Sie einfach den langen Link in das Textfeld ein und drücken Sie die Schaltfläche « <strong>Link kürzen</strong> ». Die gekürzte Version wird in dem angezeigten Feld erscheinen.<br>
            Um den kurzen Link zu verwenden, kopieren Sie ihn und fügen Sie ihn in Ihren Browser ein, und der Benutzer wird automatisch zum ursprünglichen Link weitergeleitet.<br>
            Um die Statistiken anzuzeigen, drücken Sie die « <strong>'.lng("front_information").'</strong> »-Schaltfläche.</p>
            <h3>Beispiel</h3>
            <p class="pad">Wenn Sie einen kurzen Link für <a href="https://www.google.com/search?client=firefox-b-d&q=come+si+accorciano+i+link%3F">https://www.google.com/search?client=firefox-b-d&q=come+si+accorciano+i+link%3F</a> erstellen möchten, 
            geben Sie ihn in das Textfeld ein und drücken Sie die « Kürzen »-Schaltfläche. Sobald Sie den kurzen Link haben, verwenden Sie ihn direkt in Ihrem Browser, um das Ergebnis zu sehen.</p>';
        case "site_index": return '
            <header class="bigtitle">
                <h1>Kürzen, Teilen, Verfolgen!</h1>
                <p class="bigsubtitle">Ein Open-Source-Projekt zur Link-Verwaltung</p>
            </header>
            
            <div class="container">
                <main>
                    <h2>Warum ist es nützlich und warum kürzen viele ihre Links?</h2>
                    <p>Ein kürzerer Link ist einfacher zu teilen und optisch sauberer. Viele Dienste wie soziale Medien und Messaging-Apps beschränken die Anzahl der Zeichen in Beiträgen oder Nachrichten, sodass lange URLs unhandlich und schwer lesbar werden können.</p>
                    <p>Ein weiterer Vorteil ist die dynamische Link-Verwaltung: Mit einem Kürzungsdienst kannst du das Ziel deines Links auch nach dem Teilen ändern, ohne alle Stellen, an denen er veröffentlicht wurde, aktualisieren zu müssen.</p>
                    <p>Schließlich kannst du die Klicks verfolgen, den Traffic analysieren, herausfinden, woher die Nutzer kommen, und deine Teilen-Strategie optimieren.</p>
                    <p>Unser Dienst ist nicht auf manuelle Nutzung beschränkt: Dank offener APIs können Entwickler und Unternehmen die Link-Generierung und -Verwaltung in ihre eigenen Systeme integrieren.</p>
                    
                    <h2>Wie funktioniert es?</h2>
                    <ul class="list">
                    <li>🔒 Um unseren Link-Kürzungsdienst zu nutzen, musst du ein registrierter Nutzer sein.</li>
                    <li>1️⃣ Füge auf deiner Hauptseite deinen langen Link in das obere Feld <strong>ein</strong>.</li>
                    <li>2️⃣ <strong>Klicke auf "Kürzen"</strong> und du erhältst eine kurze URL sowie einen entsprechenden QR-Code.<div style="padding-left:20px">- Es wird ein zufälliger kurzer Link generiert, aber <strong>du kannst ihn ändern</strong> und einen einprägsameren verwenden, der besser passt.</div></li>
                    <li>3️⃣ <strong>Teile ihn</strong> überall: soziale Medien, E-Mails, Nachrichten.</li>
                    <li>4️⃣ <strong>Verfolge</strong> die Besuche mit erweiterten Statistiken, die dir Informationen wie Datum, Uhrzeit und geografische Position des Nutzers, der geklickt hat, liefern.</li>
                    <li>💡 <strong>Unterstützt QR-Codes</strong> für sofortiges Teilen!</li>
                    </ul>
                    <div class="form-group center-content">
                        <a href="/_this_prj_login" class="btn btn-primary">Anmelden</a>
                        <a href="/register" class="btn btn-secondary">Registrieren</a>
                    </div>
                </main>
            
                <section>
                    <h2>Warum dieses Projekt wählen?</h2>
                    <ul class="list">
                        <li>💻 <strong>Open Source und Kostenlos</strong> - Der Code ist für alle Nutzer verfügbar.</li>
                        <li>🔍 <strong>Transparent und Sicher</strong> - Kein verstecktes Tracking, keine invasiven Praktiken, kreativ und anders geschrieben, um Hacking-Risiken zu reduzieren.</li>
                        <li>🛠 <strong>Anpassbar</strong> - Modifizierbar, um deinen Bedürfnissen gerecht zu werden.</li>
                        <li>👥 <strong>Von der Community Unterstützt</strong> - Erhalte Unterstützung und trage mit Verbesserungen bei.</li>
                        <li>📡 <strong>Offene APIs</strong> - Ideal für Entwickler und Unternehmen.</li>
                        <li>🚀 <strong>Unabhängig</strong> - Keine Werbung, kein Unternehmenseinfluss, nur offene Technologie.</li>
                        <li>🌍 <strong>Europäisches Projekt</strong> - Unterstützt in den vier Hauptsprachen: Englisch, Italienisch, Französisch und Deutsch. Du kannst die Sprache oben rechts im Header einfach auswählen.</li>
                    </ul>
                </section>
                
                <section><br> 
                    <h2>Häufig Gestellte Fragen</h2>
                    <div class="accordion">
                        <div class="tab">
                            <input type="checkbox" id="faq1">
                            <label class="tab__label" for="faq1">Warum sollte ich einen Link kürzen?</label>
                            <div class="tab__content">
                                <p>Das Kürzen von Links macht sie einfacher zu teilen, verbessert die Lesbarkeit und ermöglicht es dir, ihre Leistung zu verfolgen, um zu verstehen, woher dein Traffic kommt.</p>
                            </div>
                        </div>
                        <div class="tab">
                            <input type="checkbox" id="faq2">
                            <label class="tab__label" for="faq2">Brauche ich ein Konto, um den Dienst zu nutzen?</label>
                            <div class="tab__content">
                                <p>Ja, du musst ein registrierter Nutzer sein, um kurze Links zu erstellen. Das gewährleistet Datenschutz, Sicherheit und sichert dir einen <strong>reservierten Zugang</strong> zu deinen Statistiken.</p>
                            </div>
                        </div>
                        <div class="tab">
                            <input type="checkbox" id="faq3">
                            <label class="tab__label" for="faq3">Verfallen meine Links?</label>
                            <div class="tab__content">
                                <p>Die Links bleiben unbegrenzt aktiv, es sei denn, du entscheidest dich, sie in deinen Kontoeinstellungen zu löschen, was dir volle Kontrolle über ihre Lebensdauer gibt.</p>
                            </div>
                        </div>
                        <div class="tab">
                            <input type="checkbox" id="faq4">
                            <label class="tab__label" for="faq4">Wie viel kostet der Dienst?</label>
                            <div class="tab__content">
                                <p>Dieses Projekt ist Open Source – geh auf GitHub, lade es herunter und nutze es. Wenn du die Funktionen nutzen möchtest, ohne es herunterzuladen und auf deinem eigenen Server zu installieren, folge den Anweisungen dessen, der es bereitgestellt hat.</p>
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