<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License
=====================================================================
This web app needs just Apache, PHP (74->8.3) and MySQL to work.
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
        
        // Rimuovi duplicati 
        $geoIp=geolocalizzaIP($db,array_values(array_unique($_StatIp)));
        foreach ($rows as $key => $row) {
            $ip = explode(',', $row)[0];
            $rows[$key] .= ','. $geoIp[$ip];
        }
    }
    return $rows; 
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


// OLD ROUTINE GEOLOCALIZE API DRIVEN
/*
function geolocalizzaIP(array $ips): array {
    $batchSize = 100; // Numero massimo di IP per batch
    $geoData = []; // Array per memorizzare i risultati

    // Suddividi l'array in batch di massimo 100 IP
    $ipBatches = array_chunk($ips, $batchSize);

    foreach ($ipBatches as $batch) {
        // Prepara il payload per l'API
        $post_data = json_encode(array_map(function($ip) {
            return ['query' => $ip];
        }, $batch));

        // Effettua la richiesta POST
        $ch = curl_init('http://ip-api.com/batch');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        $response = curl_exec($ch);

        // Gestisci eventuali errori di cURL
        if (curl_errno($ch)) {
            //echo 'Errore cURL: ' . curl_error($ch);
            curl_close($ch);
            continue;
        }

        curl_close($ch);

        // Decodifica la risposta JSON
        $data = json_decode($response, true);

        // Analizza i risultati dell'API
        if (is_array($data)) {
            foreach ($data as $entry) {
                $ip = $entry['query'];
                if ($entry['status'] === 'success') {
                    $geoData[$ip] = $entry['city'] . '|' . $entry['regionName'] . '|' . $entry['country'];
                } else {
                    $geoData[$ip] = lng("unavailable_data");
                }
            }
        }
    }

    return $geoData;
}
*/