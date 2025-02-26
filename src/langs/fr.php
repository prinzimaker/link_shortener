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
        case "register": return "S'inscrire";
        case "ripeti_password": return "Répéter le mot de passe";
        case "email": return "Adresse e-mail";
        case "verify": return "Vérifier";
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
                    
                    <h2>Comment ça fonctionne ?</h2>
                    <ul class="list">
                    <li>🔒 Pour utiliser notre service de raccourcissement de liens, il faut être un utilisateur enregistré.</li>
                    <li>1️⃣ Sur ta page principale, <strong>colle</strong> ton lien long dans la boîte en haut.</li>
                    <li>2️⃣ <strong>Clique sur "Raccourcir"</strong> et tu obtiendras une URL courte et un QR code correspondant.<div style="padding-left:20px">- Un lien court aléatoire sera généré, mais <strong>tu pourras le changer</strong> pour en utiliser un plus mémorisable qui te convient.</div></li>
                    <li>3️⃣ <strong>Partage-le</strong> partout : réseaux sociaux, e-mails, messages.</li>
                    <li>4️⃣ <strong>Suis</strong> les visites grâce aux statistiques avancées qui te fourniront des informations comme la date, l\'heure et la position géographique de l\'utilisateur qui a cliqué.</li>
                    <li>💡 <strong>Supporte les QR Codes</strong> pour un partage immédiat !</li>
                    </ul>
                    <div class="form-group center-content">
                        <a href="/_this_prj_login" class="btn btn-primary">Connexion</a>
                        <a href="/register" class="btn btn-secondary">Inscription</a>
                    </div>
                </main>
            
                <section>
                    <h2>Pourquoi choisir ce projet ?</h2>
                    <ul class="list">
                        <li>💻 <strong>Open Source et Gratuit</strong> - Le code est accessible à tous les utilisateurs.</li>
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