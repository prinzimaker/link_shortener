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
        case "dashboard": return "P.Controllo";
        case "user": return "Utente";
        case "password": return "Password";
        case "login": return "Accedi";
        case "send": return "Invia";
        case "giorno": return "Giorno";
        case "notte": return "Notte";
        case "sera": return "Sera";
        case "preview":return "Anteprima";
        case "username": return "Nome utente";
        case "download-data":return "Scarica i dati";
        case "daypart": return "Parte del giorno";
        case "per_fasce_orarie": return "Per fasce orarie";
        case "device": return "Dispositivo"; 
        case "source": return "Fonte";
        case "register": return "Registrati";
        case "repeat_password": return "Ripeti password";
        case "email": return "Indirizzo e-mail";
        case "user_registration": return "Registrazione utente";
        case "verify": return "Verifica";
        case "update":return "Update";
        case "autentication": return "Autenticazione";
        case "forgot_pass": return "Ho dimenticato la mia password";
        case "invalid_uid_or_pass": return "Utente o password non validi";
        case "api_loop": return "Non &egrave; possibile creare un look per ridurre il link ";
        case "api_invalid-short": return "Questo SHORT_ID non &egrave; valido.";
        case "front_insert-long": return "Inserisci qui il link lungo...";
        case "front_shorten": return "Riduci il link";
        case "change_pass_form":return "Modifica password";
        case "link_limit-reached": return "Hai raggiunto il numero massimo di link che possono essere creati. Per creare un nuovo link, devi eliminarne uno esistente.";
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
        case "front_access-data": return "Dati di accesso";
        case "front_title-detail-data": return "{{clicks}} click da {{unique}} utenti unici";
        case "not-found": return "non trovato";
        case "front_instr-small": return "Inserire il link ridotto e premere il tasto &quot;<strong>Informazioni</strong>&quot; per ottenere informazioni sul link ridotto.";
        case "front_incorrect-link":return "<strong>uri</strong> non corretto oppure loop-<strong>uri</strong> (non &egrave; possibile, ne consigliabile, accorciare un link di <strong>".getenv("URI")."</strong>)";
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
        case "delete-element":return "Elimino il link ridotto:<br><strong>{{code}}</strong><br>che ha il seguente URI:<br><strong>{{uri}}</strong>";
        case "front_instructions": return '<p><strong>Questo &egrave; un sito per la creazione di link corti.</strong></p>
                <p>Vuol dire che tu mi passi un link lungo e io ti restituisco un link corto che pu&ograve; essere sostituito al link originale.</p>
                <h3>Come funziona?</h3>
                <p class="pad">Per creare un link corto, basta inserire il link lungo nel campo di testo e premere il pulsante "<strong>Riduci il link</strong>", la versione accorciata sar&agrave; visualizzata nella casella che comparirà.<br>
                Per utilizzare il link corto, basta copiarlo e incollarlo nel browser e l&apos;utilizzatore verr&agrave; reindirizzato automaticamente verso il link originale.<br>
                Per visualizzare le statistiche premere il pulsante "<strong>'.lng("front_information").'</strong>".</p>
                <h3>Esempio</h3>
                <p class="pad">Se vuoi creare un link corto per <a href="https://www.google.com/search?client=firefox-b-d&q=come+si+accorciano+i+link%3F">https://www.google.com/search?client=firefox-b-d&q=come+si+accorciano+i+link%3F</a>, 
                inseriscilo nel campo di testo e premi il pulsante "Shorten", quando avrai il link corto usalo direttamente sul browser per visualizzare il risultato.</p>';
        case "site_index": return '
            <header class="bigtitle">
                <h1>Accorcia, Condividi, Monitora!</h1>
                <p class="bigsubtitle">Un progetto open-source per la gestione dei link</p>
            </header>
            <div class="container">
                <main>
                    <h2>Perch&eacute; &egrave; utile e perch&eacute; molti usano accorciare i link?</h2>
                    <p>Un link pi&ugrave; corto &egrave; pi&ugrave; pratico da condividere e visivamente pi&ugrave; pulito. Molti servizi come social media e app di messaggistica limitano il numero di caratteri nei post o nei messaggi, quindi un URL lungo potrebbe risultare ingombrante e poco leggibile.</p>
                    <p>Un altro vantaggio &egrave; la gestione dinamica del link: con un servizio di accorciamento, puoi modificare la destinazione del tuo link anche dopo averlo condiviso, senza dover aggiornare tutti i luoghi in cui &egrave; stato pubblicato.</p>
                    <p>Infine, puoi monitorare i clic effettuati, analizzare il traffico, scoprire da dove provengono gli utenti e ottimizzare la tua strategia di condivisione.</p>
                    <p>Il nostro servizio non &egrave; limitato all\'uso manuale: grazie alle API aperte, gli sviluppatori e le aziende possono integrare la generazione e la gestione dei link nei propri sistemi.</p>
                    <div class="form-group center-content">
                        <a href="/_pls_fnc_login" class="btn btn-primary">Accedi</a>
                        <a href="/_pls_fnc_register" class="btn btn-secondary">Registrati</a>
                    </div>
                    <h2>Come funziona?</h2>
                    <div style="padding-left:30px;">
                        <h3>Via web</h3>
                        <ul class="list">
                            <li>🔒 Per poter utilizzare il nostro servizio di accorciamento link, &egrave; necessario essere un utente registrato.</li>
                            <li>1️⃣ Nella tua main page <strong>Incolla</strong> il tuo link lungo nel box in alto.</li>
                            <li>2️⃣ <strong>Clicca su "Accorcia"</strong> e otterrai un URL breve e un qr code corrispondente.<div style="padding-left:20px">- Verr&agrave; generato un link corto casuale, ma <strong>potrai cambiarlo</strong> usandone uno mnemonico pi&ugrave; adatto.</div></li>
                            <li>3️⃣ <strong>Condividilo</strong> ovunque: social, email, messaggi.</li>
                            <li>4️⃣ <strong>Monitora</strong> le visite grazie alle statistiche avanzate che ti forniranno informazioni quali: data, ora e posizione geografica dell\'utente che ha eseguito il click.</li>
                            <li>💡 <strong>Supporta QR Code</strong> per la condivisione immediata!</li>
                        </ul>
                        <h3>Via API</h3>
                        <div style="padding-left:30px;">
                            💻 <strong>Funziona via API</strong>: integra il nostro servizio nei tuoi progetti utilizzando le nostre API potenti e flessibili.
                            <br>vedi: ><a class="nav-item" style="color:#A33" href="/pls_swagu" target="_blank">OpenAPI doc</a> - oppure: ><a class="nav-item" style="color:#A33" href="/pls_redoc" target="_blank">Redoc API doc</a>
                        </div>
                    </div>
                </main>
                <section>
                    <h2>Perché scegliere questo progetto?</h2>
                    <ul class="list">
                        <li>💻 <strong>Open Source e Gratuito</strong> - Il codice è disponibile per tutti gli utenti.&nbsp;&gt;<a class="nav-item" style="color:#A33" href="/pls_about" target="_blank">GitHub</a></li>
                        <li>🔍 <strong>Trasparente e Sicuro</strong> - Nessun tracciamento nascosto, nessuna pratica invasiva, scritto in modo creativo e diverso dal solito per ridurre le esperienze di hacking.</li>
                        <li>🛠 <strong>Personalizzabile</strong> - Modificabile per adattarsi alle tue esigenze.</li>
                        <li>👥 <strong>Supportato dalla Community</strong> - Ricevi supporto e contribuisci con miglioramenti.</li>
                        <li>📡 <strong>API Aperte</strong> - Ideale per sviluppatori e aziende.</li>
                        <li>🚀 <strong>Indipendente</strong> - Nessuna pubblicità, nessuna influenza aziendale, solo tecnologia aperta.</li>
                        <li>🌍 <strong>Progetto Europeo</strong> - Supportato nelle quattro lingue principali: Inglese, Italiano, Francese e Tedesco. Puoi facilmente scegliere la lingua in alto a destra nella testata.</li>
                    </ul>
                </section>
                <section><br>&nbsp;
                    <h2>Domande Frequenti</h2>
                    <div class="accordion">
                        <div class="tab">
                            <input type="checkbox" id="faq1">
                            <label class="tab__label" for="faq1">Perch&eacute; dovrei accorciare un link?</label>
                            <div class="tab__content">
                                <p>Accorciare i link li rende pi&ugrave; facili da condividere, migliora la leggibilità e permette di monitorarne le prestazioni, aiutandoti a capire da dove proviene il traffico.</p>
                            </div>
                        </div>
                        <div class="tab">
                            <input type="checkbox" id="faq2">
                            <label class="tab__label" for="faq2">Devo avere un account per usare il servizio?</label>
                            <div class="tab__content">
                                <p>S&igrave;, devi essere un utente registrato per creare link brevi. Questo garantisce la privacy, la sicurezza e assicura un accesso <strong>riservato</strong> alle tue statistiche.</p>
                            </div>
                        </div>
                        <div class="tab">
                            <input type="checkbox" id="faq3">
                            <label class="tab__label" for="faq3">I miei link scadono?</label>
                            <div class="tab__content">
                                <p>I link rimangono attivi indefinitamente a meno che tu non scelga di eliminarli dalle impostazioni del tuo account, dandoti il pieno controllo sulla loro durata.</p>
                            </div>
                        </div>
                        <div class="tab">
                            <input type="checkbox" id="faq4">
                            <label class="tab__label" for="faq4">Quanto costa il servizio?</label>
                            <div class="tab__content">
                                <p>Questo progetto è open source, vai su github, scaricalo e usalo. Se invece vuoi usarne le funzionalità senza scaricare e installare il progetto su un tuo server, segui le indicazioni di chi lo ha reso disponibile.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        ';
        case "0select": return "Seleziona ";
        case "0star": return "la stella";
        case "0house": return "la casetta";
        case "0computer": return "il monitor di computer";
        case "0car": return "l'auto";
        case "0robot": return "la testa del robot";
        case "0cloud": return "la nuvoletta";
        case "0lock": return "il catenaccio";
        case "0rocket": return "il razzo";
        case "0heart": return "il cuore";
        case "0tree": return "l'albero";
        case "0plane": return "l'aeroplano'";
        case "0envelope": return "la busta";
        case "0eye": return "l'occhio'";
        case "0nouid": return "Il valore di user-id &egrave; obbligatorio.";
        case "0noemail": return "L'e-mail &egrave; obbligatoria.";
        case "0nopass": return "Devi inserire una password.";
        case "0nospam": return "Devi selezionare la giusta icona antispam.";
        case "0invemail": return "Inserisci un indirizzo email valido.";
        case "0smallpass": return "La password deve essere di almeno 8 caratteri.";
        case "0diffpass": return "Le password non coincidono.";
        case "1diffpass": return "password DIVERSE.";
        case "0poor": return "Insicura";
        case "0mean": return "Media";
        case "0strong": return "Sicura";
        case "0regok": return "<h2>Registrazione completata con successo</h2><p>Abbiamo inviato un'email di verifica all'indirizzo {{email}}. Controlla la tua casella di posta elettronica e segui le istruzioni per completare la registrazione.</p>";
        case "0uexist": return "<h2>Utente gi&agrave; presente</h2><p>Salve, esiste gi&agrave; un utente con questo indirizzo e-mail. Ha per caso <a href='/_pls_fnc_fgtpass'>dimenticato la password</a>?.</p>";
        case "email_not_verified": return "<h2>Attenzione</h2><p>Il suo indirizzo email non &egrave; stato verificato.<br>Controlli la sua casella di posta elettronica e segua le istruzioni per completare la registrazione, grazie.</p>";
        case "email_verified": return "<h2>Verifica completata</h2><p>Bene, il suo indirizzo email &egrave; stato verificato, acceda quindi con le sue credenziali.</p>";
        case "email_needed":return "Per potere richiedere il cambio della password, devi inserire il tuo indirizzo email.";
        case "subjverifyemail":return "Verifica il tuo indirizzo email";
        case "subjchangepass":return "Richiesta cambio password";
        case "change_pass_msg": return "<h2>Attenzione</h2><p>Ha richiesto il cambio della password, se il suo indirizzo e-mail corrisponde ad un utente registrato, ricever&agrave; a breve un messaggio all'indirizzo <strong>{{email}}</strong> con le istruzioni necessarie a cambiare la sua password.</p>";
        case "check-email-body": return "<h1>Verifica la tua Email</h1><p>Clicca sul seguente link per verificare il tuo indirizzo email:</p>{{link}}<p>Se non hai richiesto la registrazione, ignora questa email.</p>";
        case "chngpass-email-body": return "<h1>Cambio password</h1><p>Gentile {{username}},<br>&egrave; stato richiesto il cambio password del suo account.<br>Se la richiesta &egrave; sua, clicchi sul seguente link:</p>{{link}}<p>.Se non ha fatto questa richiesta, semplicemente ignori questa email.</p>";
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