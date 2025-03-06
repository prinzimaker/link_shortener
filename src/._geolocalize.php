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
v1.4.0 - Aldo Prinzi - 03 Mar 2025
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

