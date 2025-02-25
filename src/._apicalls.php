<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License     
=====================================================================
This web app needs just Apache, PHP (74->8.3) and MySQL to work.
---------------------------------------------------------------------
This file contains all the functions needed to handle api calls
-
v1.3.0 - Aldo Prinzi - 26 Jan 2025
    - check for user key
=====================================================================
*/
function replyToApiCall ($db){
    header('Content-Type: application/json');
    $user = isset($_GET['key']) ? $_GET['key'] : null;
    $uri = isset($_GET['uri']) ? $_GET['uri'] : null;
    $short = isset($_GET['short']) ? $_GET['short'] : null;
    $calls = isset($_GET['calls']) ? $_GET['calls'] : null;
    $cust_id=0;
    $userData=[];
    if (is_null($user)){
        die( json_encode(array('status' => 'error', 'message' => 'No API key provided: you need at least one key to use this service.')));
    } else {
        $user = filter_var($user, FILTER_SANITIZE_STRING);
        $userData=$db->getUserByApiKey($user);
        if (!isset($userData["cust_id"])){
            die( json_encode(array('status' => 'error', 'message' => 'Invalid API key or inactive account.')));
        }
    }
    $cust_id=$userData["cust_id"];
    $response = array();
    if ($user && ($uri || $short|| $calls)) {
        // Sanitizza gli input
        $user = filter_var($user, FILTER_SANITIZE_STRING);
        $uri = filter_var($uri, FILTER_SANITIZE_URL);
        $short = filter_var($short, FILTER_SANITIZE_URL);
        $calls = filter_var($calls, FILTER_SANITIZE_URL);
        if (filter_var($uri, FILTER_VALIDATE_URL)) {
            // Controlla che l'URI non punti allo stesso url per evitare loop
            if (checkIfSelfUri($uri)!="") {
                foreach($_GET as $key=>$data){
                    if ($key!="key" && $key!="uri")
                        $uri.="&".$key."=".$data;
                }
                $code=$db->createShortlink($uri,$cust_id);
                $shortUrl = getenv("URI").$code;
                $response['status'] = 'success';
                $response['original_url'] = $uri;
                $response['short_url'] = $shortUrl;
            } else {
                $response['status'] = 'error';
                $response['message'] = lng("api_loop").getenv("URI").' URL.';
            }
        } elseif (filter_var($short, FILTER_SANITIZE_STRING)) {
            $res=$db->getShortlinkInfo($short,$cust_id);
            if (empty($res)){
                $response['status'] = 'error';
                $response['message'] = lng("api_invalid-short");
            } else {
                $response['status'] = 'success';
                $response['original_url'] = $res["full_uri"];
                $response['created'] = $res["created"];
                $response['calls_count'] = $res["calls"];
            }
        } elseif (filter_var($calls, FILTER_SANITIZE_STRING)) {
            $rows=getCallsLog($db,$calls, $cust_id);
            if (!isset($rows) || empty($rows)){
                $response['status'] = 'error';
                $response['message'] = 'Invalid SHORT_ID provided.';
            } else {
                $response['status'] = 'success';
                $response['short_id'] = $calls;
                $response['calls_log'] = $rows;
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Invalid URI provided.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Mandatory parameters missing.';
    }
    die( json_encode($response));
}

