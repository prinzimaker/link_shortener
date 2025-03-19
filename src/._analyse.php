<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License
=====================================================================
This web app needs just Apache, PHP (7.4->8.3) and MySQL to work.
---------------------------------------------------------------------
This class contains all the Geolocalisation functions
-
v1.4.2 - Aldo Prinzi - 17 Mar 2025
---------
UPDATES
---------
2025.02.24 - Removed any limit, and use database
             See /ip2locatioon folder for more info
2025.02.13 - Limited geolocation requests to max 200 records per link
=====================================================================
*/

// Elenco firme note dei principali bot social
$knownBots = [
    'facebookexternalhit', 'facebot', 'twitterbot', 'linkedinbot', 'whatsapp',
    'slackbot', 'telegrambot', 'pinterest', 'discordbot', 'googlebot',
    'bingbot', 'applebot', 'tiktokbot', 'yandexbot', 'baiduspider',
    'duckduckbot', 'ahrefsbot', 'mj12bot', 'semrushbot', 'rogerbot',
    'exabot', 'ia_archiver', 'msnbot', 'slurp', 'seznambot', 'dotbot',
    'petalbot', 'sogou', 'uptimebot', 'sitecheckerbot', 'linkdexbot',
    'blexbot', 'netcraft', 'screaming frog', 'webmeup-crawler', 'spbot',
    'trendictionbot', 'omgili', 'komodiabot', 'scrapy', 'curl', 'wget',
    'python-requests', 'linkchecker', 'brokenlinkcheck', 'xenu', 'linkwalker',
    'w3c-linkchecker', 'deadlinkchecker', 'integrity', 'linklint',
    'sitelinkvalidator', 'checkbot', 'linkalarm', 'rel-link-checker', 'linkscan',
    'w3c_validator', 'css-validator', 'validator.nu', 'htmlvalidator', 'wave',
    'totalvalidator', 'cynthiasays', 'achecker', 'sortsite', 'powermapper',
    'unicorn', 'markupvalidator', 'linkvalidator', 'feedburner', 'rssowl',
    'feedly', 'inoreader', 'newsblur', 'theoldreader', 'bazqux', 'tiny tiny rss',
    'liferea', 'akregator', 'netvibes', 'bloglines', 'superfeedr', 'g2reader',
    'freshreader', 'rssbandit', 'vienna', 'sharpreader', 'feeddemon', 'newsgator',
    '360spider', 'admantx', 'alexa', 'archive', 'aspiegelbot', 'bytespider',
    'coccocbot', 'daum', 'findxbot', 'getleft', 'go-http-client', 'grapeshot',
    'httrack', 'java', 'magpie-crawler', 'mauibot', 'mediapartners-google',
    'naverbot', 'outwit', 'qwantify', 'sistrix', 'turnitinbot', 'wotbox', 'zyte'
];

$socialNetworks = [
    ['facebook',"FCBK"]   ,
    ['twitter',"TWIT"]    ,
    ['github',"GHHB"]     ,
    ['quora',"QRA"]      ,
    ['amazon',"AMZN"]     ,
    ['instagram',"INSG"]  ,
    ['linkedin',"L-IN"]   ,
    ['tiktok',"TKTK"]     ,
    ['youtube',"YTBE"]    ,
    ['pinterest',"PNTS"]  ,
    ['google',"GGLE"]     ,
    ['reddit',"RDDT"]     ,
    ['snapchat',"SNPC"]   ,
    ['whatsapp',"WHAP"]   ,
    ['telegram',"TGRM"]   ,
    ['t.me',"TGRM"]       ,
    ['bit.ly',"BTLY"]      ,
    ['tinyurl',"TURL"]    ,
    ['ow.ly',"OWLY"]      ,
    ['is.gd',"INSM"]      ,
    ['buff.ly',"BFLY"]    ,
    ['rebrand.ly',"RBLY"] ,
    ['cutt.ly',"CTLY"]    ,
    ['t.co',"TWIT"]       ,
    ['m.me',"MMLY"]       ,
    ['l.facebook',"FCBK"] ,
    ['fb.me',"FCBK"]      ,
    ['fb.com',"FCBK"]     ,
    ['fb.watch',"FCBK"]   ,
    ["web.telegram.org","TGRM"]
];

function getCallsLog($db,$short_id,$cust_id=""){
    global $socialNetworks;
    if (trim($cust_id)==""){
        $userData=[];
        if (isset($_SESSION["user"]))
            $userData=$_SESSION["user"];
        if (isset($userData["cust_id"]))
            $cust_id=$userData["cust_id"];
    }
    $rows=$db->getDownloadInfo($short_id,$cust_id);
    if (is_array($rows)){
        usort($rows, function ($a, $b) {
            $dateA = strtotime(explode(',', $a)[1]);
            $dateB = strtotime(explode(',', $b)[1]);
            return $dateB <=> $dateA;
        });
        $_StatIp = array_map(function($entry) {
            return explode(',', $entry)[0];
        }, $rows);
        
        // Rimuovi duplicati 
        $geoIp=geolocalizzaIP($db,array_values(array_unique($_StatIp)));
        $vars=[];
        foreach ($rows as $key => $row) {
            $vars[$key]= array_pad(explode(',', $row),5,"");
            $vars[$key][2]=processReferer($vars[$key][2],$socialNetworks);
            $vars[$key]=array_merge(array_slice($vars[$key],0,1) , array($geoIp[$vars[$key][0]]),array_slice($vars[$key],1,4),array($vars[$key][5]??bin2hex(md5($vars[$key][0]))));
            if ($vars[$key][4]=="bot"){
                $vars[$key][3]="[bot]";
                $vars[$key][4]=="crawler";
            }
        }
    }
    return $vars; 
}

function processReferer($entry,$socialNetworks) {
    // Supponiamo che l'array abbia chiavi 'ip' e 'referer'
    $referer = $entry ?? '';
    // Se non c'è referer, restituisci come è
    if (empty($referer) || $referer==='[direct]') {
        return '[direct]';
    }
    // Estrai il dominio dal referer usando parse_url
    $parsedUrl = parse_url($referer);
    $domain = strtolower($parsedUrl['host'] ?? '');
    foreach ($socialNetworks as $SN) {
        if (stripos($domain, $SN[0]) !== false) 
            return $SN[1];
    }
    return $domain;
}

function geolocalizzaIP($db,array $ips): array {
    $geoData = []; 
    $stmt=$db->getPreparedStatement("SELECT * FROM ip2location WHERE ip_from <= :myip ORDER BY ip_from DESC LIMIT 1;");
    foreach ($ips as $batch) {
        $ipParts=explode(".",$batch);
        if (is_array($ipParts) && count($ipParts)>3){
            $thisIp=$ipParts[0]*256*256*256+$ipParts[1]*256*256+$ipParts[2]*256+$ipParts[3];
            $stmt->bindParam(':myip', $thisIp);
            $stmt->execute();
            $result = $stmt->fetch();

            // Analizza i risultati dell'API
            if (is_array($result)) {
                $geoData[$batch] = $result['city'] . '|' . $result['region'] . '|' . $result['state_id'];
            } else {
                $geoData[$batch] = "unkown |" . $thisIp ;
            }
        }
    }
    return $geoData;
}
function getCallLogData(){
    $ip=$_SERVER['REMOTE_ADDR'];
    $ua=$_SERVER['HTTP_USER_AGENT'];
    $res=getUserAgentInfo($ua);
    $ua=str_replace([",",";","\""],["-","|","'"],$ua);
    if (isset($_SERVER["HTTP_REFERER"]))
        $ref=$_SERVER["HTTP_REFERER"];
    else
        $ref=($res[0]=="bot")?"[bot]":"[direct]";
    $log=$ip.",".date("Y-m-d H:i:s").",".$ref.",".$res[0].",".$res[1].",".md5($ip . '|' . $ua).";";
    return $log;
}

function getUserAgentInfo($userAgentSignature) {
    // Risultato di default
    $result = [
        'device' => 'unknown', // PC, tablet, phone,
        'os' => ""   // Operating System or unknown signature (robot, crawler, API, etc.)
    ];

    // Converti tutto in minuscolo per facilitare il match
    $userAgentSignature = strtolower($userAgentSignature);

    if (preg_match('/(bot|crawl|spider|facebookexternalhit|googlebot|bingbot|yahoo|baiduspider|twitterbot)/i', $userAgentSignature)) {
        $result['device'] = 'bot';
        
        // Identificazione specifica di alcuni bot conosciuti
        if (preg_match('/facebookexternalhit/i', $userAgentSignature)) {
            $result['device'] = 'bot';
            $result['os'] = 'facebook';
        } elseif (preg_match('/googlebot/i', $userAgentSignature)) {
            $result['device'] = 'bot';
            $result['os'] = 'google';
        } elseif (preg_match('/LinkedInBot/i', $userAgentSignature)) {
            $result['device'] = 'bot';
            $result['os'] = 'linkedin';
        } elseif (preg_match('/bingbot/i', $userAgentSignature)) {
            $result['device'] = 'bot';
            $result['os'] = 'bing';
        }
    }
    // --- Rilevamento del dispositivo ---
    // Phone
    elseif (preg_match('/(iphone|android|blackberry|windows phone|symbian|mobile)/i', $userAgentSignature)) {
        $result['device'] = 'phone';
    }
    // Tablet
    elseif (preg_match('/(ipad|tablet|kindle|silk|playbook)/i', $userAgentSignature)) {
        $result['device'] = 'tablet';
    }
    // PC (default se non è mobile o tablet)
    elseif (preg_match('/(windows nt|macintosh|linux|cros)/i', $userAgentSignature) && 
            !preg_match('/mobile/i', $userAgentSignature)) {
        $result['device'] = 'pc';
    }

    if (empty($result['os'])){
        // --- Rilevamento del sistema operativo ---
        if (preg_match('/windows nt ([\d\.]+)/i', $userAgentSignature, $matches)) {
            $version = $matches[1];
            $windowsVersions = [
                '10.0' => 'Windows 10/11',
                '6.3' => 'Windows 8.1',
                '6.2' => 'Windows 8',
                '6.1' => 'Windows 7'
            ];
            $result['os'] = $windowsVersions[$version] ?? 'Windows';
        }
        elseif (preg_match('/android\s?([\d\.]+)/i', $userAgentSignature, $matches)) {
            $result['os'] = 'Android ' . ($matches[1] ?? '');
        }
        elseif (preg_match('/iphone os ([\d_]+)/i', $userAgentSignature, $matches)) {
            $result['os'] = 'iOS ' . str_replace('_', '.', $matches[1]);
        }
        elseif (preg_match('/ipad; cpu os ([\d_]+)/i', $userAgentSignature, $matches)) {
            $result['os'] = 'iOS ' . str_replace('_', '.', $matches[1]);
        }
        elseif (preg_match('/mac os x ([\d_]+)/i', $userAgentSignature, $matches)) {
            $result['os'] = 'macOS ' . str_replace('_', '.', $matches[1]);
        }
        elseif (preg_match('/linux/i', $userAgentSignature)) {
            $result['os'] = 'Linux';
        }
        elseif (preg_match('/cros/i', $userAgentSignature)) {
            $result['os'] = 'Chrome OS';
        }
    }
    return [$result['device'],str_replace([",",";","\""],["-","|","'"],$result['os'])];
}

function recInnerCall($userId){
    $db=NEW Database();
    $logRec=explode(",",getCallLogData());
    $geo=str_replace("|",",",geolocalizzaIP($db,[$logRec[0]]));
    $log=[explode(" ",$logRec[1])[1],$logRec[0],$geo[$logRec[0]],$userId,str_replace(getenv("URI"),"",$logRec[2]),$logRec[3],$logRec[4],$logRec[5]];
    $db->registerPLScall(implode(",",$log));
}

function isUserAgent(){
    global $knownBots;
    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
    foreach ($knownBots as $bot) {
        if (strpos($userAgent, $bot) !== false) {
            return true;
        }
    }
    return false;
}

function getUserAgentContent($uri) {
    
    $db = new Database();
    $res=$db->getFullLink($uri,true);
    $info=$db->getShortlinkAltInfo($uri);
    if (is_null($res) || empty($res["uri"])){
        // Handle 410 Gone error
        $res='<div class="container404"><div class="copy-container404 center-xy404"><p class="p404"><span style="font-size:2em;font-weight:900">'.$uri.' ???</span><br>410: Gone -or- page not found.</p><span class="handle404"></span></div></div>';
        http_response_code(410);
        die( '<html class="html404"><head><title>Page not found (hex 32768)</title><link rel="stylesheet" type="text/css" href="/assets/site.css"></head><body class="body404">'.$res.'</body></html>');
    }
    // Recupera il contenuto della pagina originale
    $originalUrl = $res["uri"];
    $goToLink="<p><a href=\"" . $originalUrl . "\">Visit the original page</a></p>";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $originalUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Segue i redirect
    curl_setopt($ch, CURLOPT_MAXREDIRS, 3); // Segue al massimo 5 redirect
    curl_setopt($ch, CURLOPT_HEADER, true); // Include gli header nella risposta
    $response = curl_exec($ch);

   // Separa header e body
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $headerSize);
    $htmlContent = substr($response, $headerSize);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);

    // Val  
    $title = $info["tit"];
    $description = $info["dsc"];
    $image = $info["img"];
    $isVideo= (bool) $info["imgvid"];
    $goToLink="";

    // Controlla se il contenuto è un'immagine
    if ($contentType && strpos($contentType, 'image/') === 0) {
        // È un'immagine: usa l'URL come immagine e crea valori di default
        if ($title=="")
            $title = "Image from " . parse_url($originalUrl, PHP_URL_HOST);
        if ($image=="")
            $image = $originalUrl; // L'URL stesso è l'immagine
    } elseif ($contentType && strpos($contentType, '/pdf') !== false) {
        if (empty($description)) $description = "Downloadable PDF document";
        if (empty($title)) $title = "PDF Document - " . parse_url($originalUrl, PHP_URL_HOST);
        if (empty($image)) $image = "https://flu.lu/assets/adobepdf.png"; 
        $goToLink="<p><a href=\"" . $originalUrl . "\">click to download<br><img style='height:32px;width:auto' src='https://flu.lu/assets/adobepdf.png'></a></p>";
    } elseif ($htmlContent !== false) {
        // Non è un'immagine: analizza l'HTML
        $doc = new DOMDocument();
        @$doc->loadHTML($htmlContent);

        $titles = $doc->getElementsByTagName('title');
        if ($titles->length > 0) {
            $titles = $titles->item(0)->nodeValue;
        }
        if (empty($title)) $title = $titles;

        $metaTags = $doc->getElementsByTagName('meta');
        foreach ($metaTags as $meta) {
            $name = $meta->getAttribute('name');
            $property = $meta->getAttribute('property');
            $content = $meta->getAttribute('content');

            if (strtolower($name) === 'description' && !empty($content)) {
                $description .= "\n".$content;
            }
            if ($property === 'og:title' && !empty($content)) {
                $title = str_replace ($content,"",$title)."\n".$content;
            }
            if ($property === 'og:description' && !empty($content)) {
                $description .= "\n".$content;
            }
            if (empty($image) && $property === 'og:image' && !empty($content)) {
                $image = $content;
            }
        }
    }
    if (empty($title)) $title = "Link Shortened";
    // Costruisci la pagina HTML come stringa
    $html = "<!DOCTYPE html>
    <head>
        <meta charset=\"UTF-8\">
        <title>" . htmlspecialchars($title) . "</title>
        <meta name=\"description\" content=\"" . htmlspecialchars($description) . "\">
        <meta property=\"og:title\" content=\"" . htmlspecialchars($title) . "\">
        <meta property=\"og:description\" content=\"" . htmlspecialchars($description) . "\">";
    if (!empty($image)) {
        if ($isVideo)
            $html .= "\n<meta property=\"og:video\" content=\"" . $image . "\">";
        else
            $html .= "\n<meta property=\"og:image\" content=\"" . $image . "\">";
    }
    $html .= "\n<meta property=\"og:url\" content=\"" . $originalUrl . "\">";
    $html .= "    
        <meta property=\"og:site_name\" content=\"flu.lu\">
        <meta property=\"og:type\" content=\"website\">
        <meta name=\"twitter:card\" content=\"summary_large_image\">
        <meta name=\"twitter:title\" content=\"" . htmlspecialchars($title) . "\">
        <meta name=\"twitter:url\" content=\"" . $originalUrl . "\">
        <meta name=\"twitter:description\" content=\"" . htmlspecialchars($description) . "\">";
        if (!empty($image)) {
            if ($isVideo)
                $html .= "\n<meta name=\"twitter:video\" content=\"" .$image . "\">";
            else
                $html .= "\n<meta name=\"twitter:image\" content=\"" .$image . "\">";
    }
    $html .= "
        <meta name=\"twitter:site\" content=\"@flu_lu\">
    </head>
    <body>
        <h1 style='font-size:1.4em'>" . htmlspecialchars($title) . "</h1>
        <p>" . htmlspecialchars($description) . "</p>";
    if (!empty($image)){
        if ($isVideo)
            $html .= "<video width='450' autoplay muted loop alt=\"Preview Video\"><source src=\"".$image."\" type=\"video/mp4\">This browser does not support videos.</video>";
        else 
            $html .= "\n<img src=\"" . $image . "\" alt=\"Preview Image\">";
    }
    $html .= $goToLink."
        <h2 style='font-size:0.7em'><hr size=1 color=\"silver\">Link shortened by <a href=\"https://flu.lu\">flu.lu</a></h2>
    </body>
</html>";
    return $html;
}

function execRedirect($uri){
    if (!empty($uri)){
        $db = new Database();
        $res=$db->getFullLink($uri);
        if (!is_null($res) && !empty($res["uri"])){
            ignore_user_abort(true);
            http_response_code(307);
            header("Location: ".$res["uri"]);
            flush();
            $uri = $res["uri"];
            $log = $res["log"];
            // Register shutdown function without parameters
            register_shutdown_function(function () use ($uri, $log) {
                sleep(1);
                callIfThisEvent($uri, $log);
            });            
            exit;
        }
        // Handle 410 Gone error
        $res='<div class="container404"><div class="copy-container404 center-xy404"><p class="p404"><span style="font-size:2em;font-weight:900">'.$uri.' ???</span><br>410: Gone -or- page not found.</p><span class="handle404"></span></div></div>';
        http_response_code(410);
        die( '<html class="html404"><head><title>Page not found (hex 32768)</title><link rel="stylesheet" type="text/css" href="/assets/site.css"></head><body class="body404">'.$res.'</body></html>');
    }
}

