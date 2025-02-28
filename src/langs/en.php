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
        case "send": return "Send";
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
        case "update":return "Update";
        case "change_pass_form":return "Change password";
        case "repeat_password": return "Repeat password";
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
            case "site_index": return '
            <header class="bigtitle">
                <h1>Shorten, Share, Track!</h1>
                <p class="bigsubtitle">An open-source project for link management</p>
            </header>
            
            <div class="container">
                <main>
                    <h2>Why is it useful and why do many people shorten links?</h2>
                    <p>A shorter link is easier to share and visually cleaner. Many services like social media and messaging apps limit the number of characters in posts or messages, so a long URL can become cumbersome and hard to read.</p>
                    <p>Another advantage is dynamic link management: with a shortening service, you can change the destination of your link even after sharing it, without needing to update it everywhere it\'s been posted.</p>
                    <p>Finally, you can track the clicks, analyze traffic, discover where users are coming from, and optimize your sharing strategy.</p>
                    <p>Our service isn\'t limited to manual use: thanks to open APIs, developers and businesses can integrate link generation and management into their own systems.</p>
                    
                    <div class="form-group center-content">
                        <a href="/_this_prj_login" class="btn btn-primary">Log In</a>
                        <a href="/_this_prj_register" class="btn btn-secondary">Sign Up</a>
                    </div>
                    <h2>How does it work?</h2>
                    <div style="padding-left:30px;">
                        <h3>Via web</h3>
                        <ul class="list">
                            <li>🔒 To use our link shortening service, you need to be a registered user.</li>
                            <li>1️⃣ On your main page, <strong>paste</strong> your long link into the box at the top.</li>
                            <li>2️⃣ <strong>Click "Shorten"</strong> and you’ll get a short URL and a corresponding QR code.<div style="padding-left:20px">- A random short link will be generated, but <strong>you can change it</strong> to a more memorable one.</div></li>
                            <li>3️⃣ <strong>Share it</strong> anywhere: social media, email, messages.</li>
                            <li>4️⃣ <strong>Monitor</strong> visits with advanced statistics that provide details such as: date, time, and geographic location of the user who clicked.</li>
                            <li>💡 <strong>Supports QR Code</strong> for instant sharing!</li>
                        </ul>
                        <h3>Via API</h3>
                        <div style="padding-left:30px;">
                            💻 <strong>Works via API</strong>: integrate our service into your projects using our powerful and flexible APIs.
                            <br>See: ><a class="nav-item" style="color:#A33" href="/pls_swagu" target="_blank">OpenAPI doc</a> - or: ><a class="nav-item" style="color:#A33" href="/pls_redoc" target="_blank">Redoc API doc</a>
                        </div>
                    </div>
                </main>
            
                <section>
                    <h2>Why choose this project?</h2>
                    <ul class="list">
                        <li>💻 <strong>Open Source and Free</strong> - The code is available to all users.&nbsp;&gt;<a class="nav-item" style="color:#A33" href="/pls_about" target="_blank">GitHub</a></li>
                        <li>🔍 <strong>Transparent and Secure</strong> - No hidden tracking, no invasive practices, written creatively and differently to reduce hacking risks.</li>
                        <li>🛠 <strong>Customizable</strong> - Modifiable to fit your needs.</li>
                        <li>👥 <strong>Community Supported</strong> - Get support and contribute improvements.</li>
                        <li>📡 <strong>Open APIs</strong> - Ideal for developers and businesses.</li>
                        <li>🚀 <strong>Independent</strong> - No ads, no corporate influence, just open technology.</li>
                        <li>🌍 <strong>European Project</strong> - Supported in the four main languages: English, Italian, French, and German. You can easily switch languages in the top right corner of the header.</li>
                    </ul>
                </section>
                
                <section><br> 
                    <h2>Frequently Asked Questions</h2>
                    <div class="accordion">
                        <div class="tab">
                            <input type="checkbox" id="faq1">
                            <label class="tab__label" for="faq1">Why should I shorten a link?</label>
                            <div class="tab__content">
                                <p>Shortening links makes them easier to share, improves readability, and lets you track their performance, helping you understand where your traffic comes from.</p>
                            </div>
                        </div>
                        <div class="tab">
                            <input type="checkbox" id="faq2">
                            <label class="tab__label" for="faq2">Do I need an account to use the service?</label>
                            <div class="tab__content">
                                <p>Yes, you need to be a registered user to create short links. This ensures privacy, security, and gives you <strong>exclusive access</strong> to your statistics.</p>
                            </div>
                        </div>
                        <div class="tab">
                            <input type="checkbox" id="faq3">
                            <label class="tab__label" for="faq3">Do my links expire?</label>
                            <div class="tab__content">
                                <p>Links remain active indefinitely unless you choose to delete them from your account settings, giving you full control over their lifespan.</p>
                            </div>
                        </div>
                        <div class="tab">
                            <input type="checkbox" id="faq4">
                            <label class="tab__label" for="faq4">How much does the service cost?</label>
                            <div class="tab__content">
                                <p>This project is open source—go to GitHub, download it, and use it. If you want to use its features without downloading and installing it on your own server, follow the instructions from whoever made it available.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        ';
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