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
v1.2.1 - Aldo Prinzi - 30 Dic 2024
---------
UPDATES
---------
=====================================================================
*/
function getCallsLog($db,$short_id){
    $rows=$db->getDownloadInfo($short_id);
    if (is_array($rows)){
        usort($rows, function ($a, $b) {
            $dateA = strtotime(explode(',', $a)[1]);
            $dateB = strtotime(explode(',', $b)[1]);
            return $dateB <=> $dateA;
        });
        $ips = array_map(function($entry) {
            return explode(',', $entry)[0];
        }, $rows);
        
        // Rimuovi duplicati e reindicizza l'array
        $geoIp=geolocalizzaIP(array_values(array_unique($ips)));
        foreach ($rows as $key => $row) {
            $ip = explode(',', $row)[0];
            $rows[$key] .= ','. $geoIp[$ip];
        }
    }
    return $rows; 
}

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
                    $geoData[$ip] = 'Informazioni non disponibili';
                }
            }
        }
    }

    return $geoData;
}