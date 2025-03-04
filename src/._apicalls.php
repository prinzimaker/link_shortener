<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License     
=====================================================================
This web app needs just Apache, PHP (7.4->8.3) and MySQL to work.
---------------------------------------------------------------------
This file contains all the functions needed to handle api calls
-
v1.4.0 - Aldo Prinzi - 03 Mar 2025
    - check for user key
=====================================================================
*/
function replyToApiCall ($db){
    header('Content-Type: application/json');

    if (count($_GET)+count($_POST)<1){
        die('{"name":"Prinzimaker\'s Link Shortener API Server","status":"ready","version":"1.4.0 - Open source","note":"You need to register and get an API KEY to use these functions. If you are a malicius hacker go away, thanks."}');
    }

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
                $response['message'] = 'Invalid SHORT_ID OR invalid KEY provided.';
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
                $response['message'] = 'Invalid SHORT_ID OR invalid KEY provided.';
            } else {
                $response['status'] = 'success';
                $response['short_id'] = $calls;
                $response['calls_log'] = $rows;
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Invalid URI provided .';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Mandatory parameters missing.';
    }
    die( json_encode($response));
}

function callIfThisEvent ($uri,$log){
    $thisServ=getenv("URI");
    $ifttt_key=getenv("IFTTT_KEY");
    if (empty($ifttt_key)){
        return false;
    }
    $url = 'https://maker.ifttt.com/triggers/click/json/with/key/'.$ifttt_key;
    $data = array('value1' => $thisServ, 'value2' => $uri, 'value3' => $log);
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/json\r\nAccept: application/json\r\nAuthorization: Bearer ".$ifttt_key."\r\n",
            'method'  => 'POST',
            'content' => json_encode($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
}

function handleIFTTTCall(){
    $db=new database();
    $chK=$_SERVER['HTTP_IFTTT_CHANNEL_KEY'];
    $svK=$_SERVER['HTTP_IFTTT_SERVICE_KEY'];
    $myK=getenv("IFTTT_KEY");
    if (stripos($_SERVER['REQUEST_URI'],"/status")!==false){
        // get Status Info
        $data=["accessToken"=>getenv("IFTTT_KEY")];
        if ($myK==$svK){
            http_response_code(200);
            die('{"status":"active","data":'.getenv("URI").'}');
        }
        http_response_code(401);
        die('{"status":"error","data":"Invalid key"}');

    }
    if (stripos($_SERVER['REQUEST_URI'],"/user/info")!==false){
        // get User Info
        $key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $usr=$db->getUserData($key, $allData=false,1);
        if (!empty($usr)){
            $data=["name"=>$usr["descr"],"id"=>$usr["descr"],"url"=>getenv("URI")];
            die('{"data":'.json_encode($data).'}');
        }
    }
    if (stripos($_SERVER['REQUEST_URI'],"/test/setup")!==false){
        if ($myK==$svK){
            http_response_code(200);
            $data=[
                "data" => [
                    "api_key" => "",
                    "access_token" => "",
                    "samples"=>[
                        "triggers"=>[
                            "click"=>[
                                "short_uri"=>"Any request",
                                "api_key"=>""
                            ],
                        ],
                        "action"=>[
                            "shortlink" => [
                                "full_uri"=>"https://example.com/long_url/link?with=parameters",
                                "api_key"=>""
                            ]
                        ],
                        "queries"=>[
                            "getlog"=>[
                                "shorturi"=>"[the flu.lu short_id]",
                                "api_key"=>""
                            ]
                        ]
                    ]
                ]
            ];
        } else {
            http_response_code(401);
            $data=["error"=>"Invalid key"];
        }
        die(json_encode($data));
    }
    http_response_code(401);
    $mess=[["message"=>"Invalid key"]];
    //$mess=str_replace(  ["[{","}]"],["[","]"],json_encode($mess));
    //$data=["status"=>"error","errors"=>"{{mess}}"];
    $data=["status"=>"error","errors"=>$mess];
    //$strrep=str_replace('"{{mess}}"',$mess,json_encode($data));
    $strrep=json_encode($data);
    die($strrep);
}
