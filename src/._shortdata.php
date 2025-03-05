<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License     
=====================================================================
This web app needs just Apache, PHP (7.4->8.3) and MySQL to work.
---------------------------------------------------------------------
This file contains all the data handling logic 
-
v1.4.0 - Aldo Prinzi - 03 Mar 2025

2025-02-24 - Added shortcode personalization
           - Added shortcode deletion
2025-02-13 - Added the html/script for the chart.js display
=====================================================================
*/
function getShortInfoDisplay($cust_id,$puri=""){
    if ($puri=="")
        $puri=$_POST["smalluri"]??$_GET["code"];
    if ($puri!=""){
        $uri=checkIfSelfUri($puri);
        if (empty($uri)){
            $db = new Database();
            $res=$db->connect();
            if ($res["conn"]){
                $uri_code=str_replace(getenv("URI"),"",$puri);
                $res=$db->getShortlinkInfo($uri_code,$cust_id);
                $content=getShortInfoContent($uri_code,$puri);
                if (empty($res)){
                    $content="<div class='alert alert-danger'>".lng("error").": <strong>uri</strong>".lng("not-found")."</div>";
                    $content.=getShortInfoContent();
                } else {
                    $formattedDate = getLangDate($res["created"]);

                    $rows=getCallsLog($db,$puri);

                    // Estrai la colonna 1
                    $ucol= array_column($rows, 6);
                    $usersCount = count(array_unique($ucol));
                    $sourcecol= array_unique(array_column($rows, 3));
                    $devicecol= array_unique(array_column($rows, 4));
                    $osyscol= array_unique(array_column($rows, 5));

                    $hret="<table cellpadding=0 cellspacing=0 class='table table-striped table-bordered table-hover'>";
                    $hret.="<tr><th colspan=2>".lng("date")."</th><th colspan=3>".lng("source")."</th><th colspan=3>".lng("geoloc")."</th><th colspan=3>".lng("device")."</th></tr><tbody>";
                    if (is_array($rows)){
                        foreach ($rows as $cols){
                            $hret.="<tr><td class='tdcont'>".$cols[2]."</td><td class='tdsep'>&nbsp;</td><td>&nbsp;</td><td class='tdcont'>".$cols[3]."</td><td class='tdsep'>&nbsp;</td><td>&nbsp;</td><td class='tdcont'>".implode(" , ",explode("|",$cols[1]))."</td><td class='tdsep'>&nbsp;</td><td>&nbsp;</td><td class='tdcont'>".$cols[4]." (".$cols[5].")</td></tr>";
                        }
                    } 
                    $hret.="</tbody></table>";

                    $content.="
                    <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js'></script>
                    <script src='https://cdn.jsdelivr.net/npm/chart.js'></script>
                    <script src='https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js'></script>
                    <script src='https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@latest/dist/chartjs-plugin-zoom.min.js'></script>
                    <div class='alert alert-info'><table width='100%'><tr><td width=85%>
                    <label>".lng("front_link-is").":</label><input type='text' class='input-text' id='originalLink' value='".$res["full_uri"]."' readonly><button class='btn btn-warning' onclick='copyShortLink()'>".lng("copy")."</button>
                    <script>function copyShortLink(){var copyText=document.getElementById('originalLink').value;navigator.clipboard.writeText(copyText).then(function(){alert('".lng("front_copied-link").": '+ copyText);},function(err){console.error('".lng("front_copy-error").":', err);});}</script>
                    <table style='padding-top:15px' width=100%><tr><td width=65%><label>".lng("front_link-created-on").":</label><input type='text' class='input-text' id='createdLink' value='".$formattedDate."' readonly></td><td>&nbsp;</td>
                    <td width=35%><label>".lng("front_was-req").":</label><input type='text' class='input-text' id='createdLink' value='".$res["calls"]." ".lng("times")."' readonly></div></td></tr></table>
                    <td width='15%' align='left' style='padding-left:30px'><img alt='short link qr-code' id='qrcode' style='border:solid 10px #fff' src='https://api.qrserver.com/v1/create-qr-code/?data=" .urlencode(getenv("URI").$uri_code). "&amp;size=100x100&amp;color=0800A0' alt='qr-code' title='qr-code' width='160px' height='160px' /></td></tr></table></div>
                    <section class='accordion'><div class='tab'><input type='radio' name='accordion-2' id='rd1'><label for='rd1' class='tab__label'>".lng('front_downloads-info')." - (".count($rows)." clicks from ".$usersCount." users)</label>
                    <div class='tab__content'><p>".$hret."</p>
                    </div><div class='tab'><input type='radio' name='accordion-2' id='rd3'><label for='rd3' class='tab__close'>".lng('close')." &times;</label></div></div></section>
                    <div style='display:none' id='chartData'>".getStatisticData($db,$puri)."</div>
                    <div style='max-width:97%; width:90%; margin-top: 20px; height:440px; max-height:450px'>
                        <canvas id='myChart' width='1000' height='440'></canvas>
                    </div>
                    <script>
                        const ctx = document.getElementById('myChart').getContext('2d');
                        const chartData = JSON.parse(document.getElementById('chartData').textContent); 
                        const data = {
                            datasets: [{
                                label: '".lng("per_fasce_orarie")."',
                                backgroundColor: '#237093',
                                borderColor: '#93A0FF',
                                data: chartData.map(entry => ({
                                    x: entry.day,
                                    y: entry.time_part,
                                    r: Math.sqrt(entry.call_count) * 1.1 
                                }))
                            }]
                        };
                        const config = {
                            type: 'bubble',
                            data: data,
                            options: {
                                plugins: {
                                    zoom: {zoom: {
                                            wheel: {enabled: true},
                                            pinch: {enabled: true},
                                            mode: 'xyz',
                                        }}
                                    },
                                    scales: {
                                    x: {type: 'time',time: {unit: 'day'},title: {display: true,text: '".lng("giorno")."'}},
                                    y: {type: 'linear',
                                        title: {display: true,text: '".lng("daypart")."'},
                                        ticks: {
                                            beginAtZero: true,
                                            callback: function(value) {if (value === 1) return '".lng("notte")."';if (value === 2) return '".lng("giorno")."';if (value === 3) return '".lng("sera")."';}
                                        },
                                        suggestedMin: 1,
                                        suggestedMax: 3
                                    }
                                }
                            }
                        };
                        new Chart(ctx, config);
                        Chart.register(ChartDataLabels); 
                        Chart.register(dateFnsAdapter);
                    </script>";
                }
            }
        } else {
            $content="<div class='alert alert-danger'>".lng("error").": <strong>uri</strong> non corretto</div>";
            $content.=getShortInfoContent($puri);
        }
    } else {
        $content="<div class='alert alert-danger'>".lng("error").": trascrivere un <strong>uri</strong> del quale ottenere le informazioni!</div>";
        $content.=getShortInfoContent();
    }
    return $content;
}

function getStatisticData($db, $short_id){
    $userData="";
    if (isset($_SESSION["user"])){
        $user_id=$_SESSION["user"]["cust_id"];
        $entries =$db->getDownloadInfo($short_id,$user_id);
        if (is_array($entries) && count($entries)>0) {
            // Separa i dati CSV
            //$entries = explode(';', trim($_StatIp, ';'));
            $chartData = [];
        
            foreach ($entries as $entry) {
                $vars= array_pad(explode(',', $entry),5,"");
                //list($ip, $dateStr) = $vars;
                if ($vars[1]){
                    try{
                        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $vars[1]);
                        $day = $dateTime->format('Y-m-d');
                        $time = $dateTime->format('H:i:s');
                
                        // Determina la parte del giorno
                        if ($time >= '00:00:00' && $time <= '06:30:00') {
                            $part = 1;
                        } elseif ($time > '06:30:00' && $time <= '20:30:00') {
                            $part = 2;
                        } else {
                            $part = 3;
                        }
                
                        // Incrementa il conteggio per il giorno e la parte del giorno
                        $key = $day . '-' . $part;
                    } catch (Exception $e){
                        $day = $dateTime->format('Y-m-d');
                        $part = 1;
                    }
                    if (!isset($chartData[$key])) {
                        $chartData[$key] = ['day' => $day, 'time_part' => $part, 'call_count' => 0];
                    }
                    $chartData[$key]['call_count']++;
                }
            }
            // Prepara i dati per l'output JSON
            $preparedData = array_values($chartData);
            return json_encode($preparedData);
        }
    } 
    return json_encode([]);
}

function delShortData(){
    $delCode=trim($_POST["code"]??$_GET["code"]);
    $content="";
    if (isset($delCode) && strlen($delCode)>2){
        $db = new Database();
        $res=$db->connect();    
        if ($res["conn"]){
            $res= $db->deleteShortCodeData($delCode);
            if ($res){
                header("Location: ".getenv("URI")."/_pls_fnc_user");
                exit();
            } else {
                $content="<div class='alert alert-danger'>".lng("error").": ".lng("database_generic_error")."</div>";
            }
        }
        
    } else {
        $content="<div class='alert alert-danger'>".lng("error").": ".lng("api_invalid-short")."</div>";
    }
    return $content;
}
function getShortLinkDisplay($uri){
    $content="";
    $shortCode="";
    try{
        $puri=$_POST["uri"];
        if ($puri!=""){
            $uri=checkIfSelfUri($puri);
            if (empty($uri)){
                $content="<div class='alert alert-danger'>".lng("error").": ".lng("front_incorrect-link")."</div>";
            } else {
                $userData="";
                if (isset($_SESSION["user"]))
                    $userData=$_SESSION["user"];
                if (empty($userData))
                    return;
                else {
                    $db = new Database();
                    $res=$db->connect();
                    $user_id=$userData["cust_id"];
                    if ($res["conn"]){
                        $UM=new UserManager();
                        if (!$UM->userLinkLimit($user_id))
                            $shortCode=$db->createShortlink($uri,$user_id);
                        else
                            $content="<div class='alert alert-danger'>".lng("error").": ".lng("link_limit-reached")."</div>";
                    } else {
                        $content="<div class='alert alert-danger'>".lng("error").": ".$res["err"]."</div>";
                    }
                }
            }
        } else {
            $content="<div class='alert alert-danger'>".lng("error").": ".lng("front_insert-correct")."</div>";
        }
    } catch (Exception $e){
        $content="<div class='alert alert-danger'>".lng("error").": ".$e->getMessage()."</div>";
    }
    return [$shortCode,$content];
}

function changeShortCode(){
    $oldCode=trim($_POST["shortcode"]??$_GET["shortcode"]);
    $newCode=trim($_POST["newcode"]??$_GET["newcode"]);

    $userData="";
    if (isset($_SESSION["user"]))
        $userData=$_SESSION["user"];
    
    if (!empty($userData) && isset($oldCode) && isset($oldCode) && (strlen($oldCode)<11 && strlen($newCode)<11) && strlen($newCode)>2){
        $db = new Database();
        $res=$db->connect();
        $content="";
        if ($res["conn"]){
            $res= $db->getShortCodeData($oldCode);
            if (is_array($res) && $res["short_id"]==$oldCode){
                $exc=$db->changeShortCode($oldCode,$newCode, getenv("URI").$newCode,$userData["cust_id"]);
                if ($exc=="AE"){
                    $content="<div class='alert alert-danger'>".lng("error").": ".lng("code_exists")."</div>";
                    $newCode=$oldCode;
                } else if ($exc=="ER"){
                    $content="<div class='alert alert-danger'>".lng("error").": ".lng("database_generic_error")."</div>";
                    $newCode=$oldCode;
                }
                //$content.=getShortInfoDisplay($newCode);
            } else {
                $content="<div class='alert alert-danger'>".lng("error").": ".lng("api_invalid-short")."</div>";
            }
        } else {
            $content="<div class='alert alert-danger'>".lng("error").": ".$res["err"]."</div>";
        }
    } else {
        $content="<div class='alert alert-danger'>".lng("error").": ".lng("api_invalid-short")."</div>";
    }
    return [$newCode,$content];
}
