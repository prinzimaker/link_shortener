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
v1.4.1 - Aldo Prinzi - 07 Mar 2025
---------
UPDATES
---------
2025.02.24 - Removed any limit, and use database
             See /ip2locatioon folder for more info
2025.02.13 - Limited geolocation requests to max 200 records per link
=====================================================================
*/
function getCallsLog($db,$short_id,$cust_id=""){
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