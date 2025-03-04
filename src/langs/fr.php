<?php
/* ==================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License     
=====================================================================
This file contains the string labels for French language
===================================================================*/
function lng($key){
    switch ($key){
        case "": return "";
        case "user": return "Identifiant utilisateur";
        case "password": return "Mot de passe";
        case "login": return "Connexion";
        case "send": return "Envoyer";
        case "register": return "S'inscrire";
        case "repeat_password": return "Répéter le mot de passe";
        case "email": return "Adresse e-mail";
        case "verify": return "Vérifier";
        case "user_registration": return "Inscription utilisateur";
        case "autentication": return "Authentification";
        case "forgot_pass": return "J'ai oublié mon mot de passe";
        case "invalid_uid_or_pass": return "Identifiant ou mot de passe invalide";
        case "api_loop": return "Pour éviter les boucles, il n'est pas possible de raccourcir un ";
        case "api_invalid-short": return "SHORT_ID fourni invalide.";
        case "front_insert-long": return "Insérez l'URL longue à raccourcir...";
        case "front_shorten": return "Raccourcir le lien";
        case "front_information":
        case "information": return "Informations";
        case "front_reduced-link": return "Lien raccourci";
        case "front_link-to-shrink": return "Lien à raccourcir";
        case "link_limit-reached": return "Vous avez atteint le nombre maximum de liens qui peuvent &ecirc;tre cr&eacute;&eacute;s. Pour cr&eacute;er un nouveau lien, vous devez supprimer un existant.";
        case "error": return "Erreur";
        case "date": return "date";
        case "copy": return "copier";
        case "times": return "fois";
        case "close": return "Fermer";
        case "language": return "Langue";
        case "new apikey": return "Nouvelle clé API";
        case "change password": return "Changer le mot de passe";
        case "update": return "Mettre à jour";
        case "ip-address": return "Adresse IP";
        case "geoloc": return "Géolocalisation";
        case "not-found": return "non trouvé";
        case "change_pass_form":return "Changement de mot de passe";
        case "unavailable_data": return "Données indisponibles";
        case "front_link-is": return "Le lien original est";
        case "front_copied-link": return "Lien copié";
        case "front_was-req": return "Et a été demandé";
        case "front_link-created-on": return "A été créé le";
        case "front_short-link-is": return "Le lien court est";
        case "front_copy-error": return "Erreur de copie du lien";
        case "front_insert-correct": return "Insérez un lien correct dans la boîte de saisie avant de cliquer sur le bouton « <strong>".lng("front_shorten")."</strong> ».";
        case "front_downloads-info": return "Informations sur le téléchargement";
        case "change_link_code": return "Modifier le code du lien";
        case "change": return "Modifier";
        case "code_exists": return "Ce code court existe déjà !";
        case "database_generic_error": return "Erreur lors de l'enregistrement dans la base de données";
        case "front_incorrect-link": return "URI incorrect ou boucle URI (il n'est pas possible de raccourcir un lien <strong>".getenv("URI")."</strong>)";
        case "front_instr-small": return "Entrez le lien raccourci et appuyez sur le bouton « <strong>Informations</strong> » pour obtenir des détails sur le lien raccourci.";
        case "front_instructions": return '<p><strong>Ceci est un site pour créer des liens courts.</strong></p>
            <p>Cela signifie que vous me fournissez un lien long, et je vous renvoie un lien court qui peut remplacer l’original.</p>
            <h3>Comment ça marche ?</h3>
            <p class="pad">Pour créer un lien court, entrez simplement le lien long dans le champ de texte et appuyez sur le bouton « <strong>Raccourcir le lien</strong> ». La version raccourcie apparaîtra dans la boîte qui s’affichera.<br>
            Pour utiliser le lien court, copiez-le et collez-le dans votre navigateur, et l’utilisateur sera automatiquement redirigé vers le lien original.<br>
            Pour voir les statistiques, appuyez sur le bouton « <strong>'.lng("front_information").'</strong> ».</p>
            <h3>Exemple</h3>
            <p class="pad">Si vous voulez créer un lien court pour <a href="https://www.google.com/search?client=firefox-b-d&q=come+si+accorciano+i+link%3F">https://www.google.com/search?client=firefox-b-d&q=come+si+accorciano+i+link%3F</a>, 
            entrez-le dans le champ de texte et appuyez sur le bouton « Raccourcir ». Une fois que vous avez le lien court, utilisez-le directement dans votre navigateur pour voir le résultat.</p>';
            case "site_index": return '
            <header class="bigtitle">
                <h1>Raccourcis, Partage, Suis !</h1>
                <p class="bigsubtitle">Un projet open-source pour la gestion des liens</p>
            </header>
            <div class="container">
                <main>
                    <h2>Pourquoi est-ce utile et pourquoi beaucoup raccourcissent leurs liens ?</h2>
                    <p>Un lien plus court est plus pratique à partager et visuellement plus net. De nombreux services comme les réseaux sociaux et les applications de messagerie limitent le nombre de caractères dans les publications ou les messages, donc une URL longue peut devenir encombrante et peu lisible.</p>
                    <p>Un autre avantage est la gestion dynamique du lien : avec un service de raccourcissement, tu peux modifier la destination de ton lien même après l\'avoir partagé, sans avoir à mettre à jour tous les endroits où il a été publié.</p>
                    <p>Enfin, tu peux suivre les clics effectués, analyser le trafic, découvrir d\'où viennent les utilisateurs et optimiser ta stratégie de partage.</p>
                    <p>Notre service n\'est pas limité à une utilisation manuelle : grâce aux API ouvertes, les développeurs et les entreprises peuvent intégrer la génération et la gestion des liens dans leurs propres systèmes.</p>
                    <div class="form-group center-content">
                        <a href="/_pls_fnc_login" class="btn btn-primary">Connexion</a>
                        <a href="/_pls_fnc_register" class="btn btn-secondary">Inscription</a>
                    </div>
                    <h2>Comment ça fonctionne ?</h2>
                    <div style="padding-left:30px;">
                        <h3>Via le web</h3>
                        <ul class="list">
                            <li>🔒 Pour utiliser notre service de raccourcissement de liens, il est n&eacute;cessaire d&apos;&ecirc;tre un utilisateur enregistr&eacute;.</li>
                            <li>1️⃣ Sur votre page principale, <strong>collez</strong> votre lien long dans la bo&icirc;te en haut.</li>
                            <li>2️⃣ <strong>Cliquez sur "Raccourcir"</strong> et vous obtiendrez une URL courte et un code QR correspondant.<div style="padding-left:20px">- Un lien court al&eacute;atoire sera g&eacute;n&eacute;r&eacute;, mais <strong>vous pourrez le modifier</strong> en utilisant un lien plus m&eacute;morisable.</div></li>
                            <li>3️⃣ <strong>Partagez-le</strong> partout : r&eacute;seaux sociaux, email, messages.</li>
                            <li>4️⃣ <strong>Surveillez</strong> les visites gr&acirc;ce aux statistiques avanc&eacute;es qui fourniront des informations telles que : date, heure et position g&eacute;ographique de l&apos;utilisateur qui a cliqu&eacute;.</li>
                            <li>💡 <strong>Supporte les QR Codes</strong> pour un partage instantan&eacute; !</li>
                        </ul>
                        <h3>Via API</h3>
                        <div style="padding-left:30px;">
                            💻 <strong>Fonctionne via API</strong> : int&eacute;grez notre service dans vos projets en utilisant nos API puissantes et flexibles.
                            <br>Voir : ><a class="nav-item" style="color:#A33" href="/pls_swagu" target="_blank">Documentation OpenAPI</a> - ou : ><a class="nav-item" style="color:#A33" href="/pls_redoc" target="_blank">Documentation Redoc API</a>
                        </div>
                    </div>
                </main>
                <section>
                    <h2>Pourquoi choisir ce projet ?</h2>
                    <ul class="list">
                        <li>💻 <strong>Open Source et Gratuit</strong> - Le code est accessible à tous les utilisateurs.&nbsp;&gt;<a class="nav-item" style="color:#A33" href="/pls_about" target="_blank">GitHub</a></li>
                        <li>🔍 <strong>Transparent et Sécurisé</strong> - Pas de suivi caché, pas de pratiques invasives, écrit de manière créative et différente pour réduire les risques de piratage.</li>
                        <li>🛠 <strong>Personnalisable</strong> - Modifiable pour s\'adapter à tes besoins.</li>
                        <li>👥 <strong>Soutenu par la Communauté</strong> - Reçois du soutien et contribue avec des améliorations.</li>
                        <li>📡 <strong>API Ouvertes</strong> - Idéal pour les développeurs et les entreprises.</li>
                        <li>🚀 <strong>Indépendant</strong> - Pas de publicités, pas d\'influence d\'entreprise, juste une technologie ouverte.</li>
                        <li>🌍 <strong>Projet Européen</strong> - Supporté dans les quatre langues principales : anglais, italien, français et allemand. Tu peux facilement choisir la langue en haut à droite dans l\'en-tête.</li>
                    </ul>
                </section>
                <section><br> 
                    <h2>Questions Fréquentes</h2>
                    <div class="accordion">
                        <div class="tab">
                            <input type="checkbox" id="faq1">
                            <label class="tab__label" for="faq1">Pourquoi devrais-je raccourcir un lien ?</label>
                            <div class="tab__content">
                                <p>Raccourcir les liens les rend plus faciles à partager, améliore la lisibilité et permet de suivre leurs performances, t\'aidant à comprendre d\'où vient ton trafic.</p>
                            </div>
                        </div>
                        <div class="tab">
                            <input type="checkbox" id="faq2">
                            <label class="tab__label" for="faq2">Dois-je avoir un compte pour utiliser le service ?</label>
                            <div class="tab__content">
                                <p>Oui, tu dois être un utilisateur enregistré pour créer des liens courts. Cela garantit la confidentialité, la sécurité et assure un accès <strong>réservé</strong> à tes statistiques.</p>
                            </div>
                        </div>
                        <div class="tab">
                            <input type="checkbox" id="faq3">
                            <label class="tab__label" for="faq3">Mes liens expirent-ils ?</label>
                            <div class="tab__content">
                                <p>Les liens restent actifs indéfiniment à moins que tu ne choisisses de les supprimer depuis les paramètres de ton compte, te donnant un contrôle total sur leur durée de vie.</p>
                            </div>
                        </div>
                        <div class="tab">
                            <input type="checkbox" id="faq4">
                            <label class="tab__label" for="faq4">Combien coûte le service ?</label>
                            <div class="tab__content">
                                <p>Ce projet est open source, va sur GitHub, télécharge-le et utilise-le. Si tu veux profiter de ses fonctionnalités sans le télécharger et l\'installer sur ton propre serveur, suis les instructions de celui qui l\'a rendu disponible.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        ';
        case "0select": return "S&eacute;lectionnez ";
        case "0star": return "l'&eacute;toile";
        case "0house": return "la maison";
        case "0computer": return "le moniteur de l'ordinateur";
        case "0car": return "la voiture";
        case "0robot": return "la t&ecirc;te de le robot";
        case "0cloud": return "le nuage";
        case "0lock": return "le cadenas";
        case "0rocket": return "la fus&eacute;e";
        case "0heart": return "le coeur";
        case "0tree": return "l'arbre";
        case "0plane": return "l'avion";
        case "0envelope": return "l'enveloppe";
        case "0eye": return "l'oeil";
        case "0nouid": return "Vous devez saisir un identifiant utilisateur.";
        case "0noemail": return "L'adresse e-mail est obligatoire.";
        case "0nopass": return "Vous devez choisir un mot de passe.";
        case "0nospam": return "Vous devez s&eacute;lectionner l'ic&ocirc;ne antispam.";
        case "0invemail": return "L'adresse e-mail n'est pas valide.";
        case "0smallpass": return "Le mot de passe doit contenir au moins 8 caract&egrave;res.";
        case "0diffpass": return "Les mots de passe ne correspondent pas.";
        case "1diffpass": return "Mots de passe diff&eacute;rents";
        case "0poor": return "Faible";
        case "0mean": return "Moyen";
        case "0strong": return "Fort";
        case "0regok": return "<h2>Inscription termin&eacute;e avec succ&egrave;s</h2><p>Nous avons envoy&eacute; un e-mail de v&eacute;rification &agrave; l'adresse {{email}}. Veuillez v&eacute;rifier votre bo&icirc;te de r&eacute;ception et suivre les instructions pour finaliser votre inscription.</p>";
        case "0uexist": return "<h2>Utilisateur d&eacute;j&agrave; pr&eacute;sent</h2><p>Bonjour, un utilisateur avec cette adresse e-mail existe d&eacute;j&agrave;. Avez-vous peut-&ecirc;tre <a href='/_pls_fnc_fgtpass'>oubli&eacute; votre mot de passe</a> ?</p>";
        case "email_not_verified": return "<h2>Attention</h2><p>Votre adresse e-mail n'a pas &eacute;t&eacute; v&eacute;rifi&eacute;e.<br>Veuillez v&eacute;rifier votre bo&icirc;te de r&eacute;ception et suivre les instructions pour finaliser votre inscription, merci.</p>";
        case "email_verified": return "<h2>Adresse e-mail v&eacute;rifi&eacute;e</h2><p>Votre adresse e-mail a &eacute;t&eacute; v&eacute;rifi&eacute";
        case "email_needed": return "Pour pouvoir demander le changement du mot de passe, vous devez saisir votre adresse e-mail.";
        case "subjchangepass":return "Changement de mot de passe";
        case "subjverifyemail":return "Vérification de l'adresse e-mail";
        case "change_pass_msg": return "<h2>Attention</h2><p>Vous avez demand&eacute; le changement du mot de passe&nbsp;; si votre adresse e-mail correspond &agrave; un utilisateur enregistr&eacute;, vous recevrez bient&ocirc;t un message &agrave; l'adresse <strong>{{email}}</strong> avec les instructions n&eacute;cessaires pour changer votre mot de passe.</p>";
        case "chngpass-email-body": return "<h1>Changement de mot de passe</h1><p>Ch&egrave;re {{username}},<br>Une demande de changement de mot de passe a &eacute;t&eacute; effectu&eacute;e pour votre compte.<br>Si cette demande vous concerne, cliquez sur le lien suivant :</p>{{link}}<p>Si vous n&apos;avez pas effectu&eacute; cette demande, veuillez ignorer simplement cet e-mail.</p>";
        case "check-email-body": return "<h1>Vérification de l'adresse e-mail</h1><p>Veuillez cliquer sur le lien suivant pour vérifier votre adresse e-mail :</p>{{link}}<p>Si vous n'avez pas demandé cette vérification, ignorez simplement cet e-mail.</p>";
        default: return "étiquette de langue $key inconnue...";
    }
}

function getLangDate($theDate){
    $date = new DateTime($theDate);
    $locale = 'fr_FR';
    $formatter = new \IntlDateFormatter(
        $locale,                         // Locale
        \IntlDateFormatter::FULL,        // Type de formatage de la date
        \IntlDateFormatter::FULL,        // Type de formatage de l'heure
        'Europe/Paris',                  // Fuseau horaire
        \IntlDateFormatter::GREGORIAN,   // Calendrier
        "EEEE dd MMMM yyyy ! HH:mm:ss"   // Modèle de formatage
    );
    return str_replace("!", "à", $formatter->format($date));
}