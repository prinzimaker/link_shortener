<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License     
=====================================================================
This web app needs just Apache, PHP (74->8.3) and MySQL to work.
---------------------------------------------------------------------
This file contains all the Link Data display builder logic 
-
v1.2.1 - Aldo Prinzi - 30 Dic 2024
=====================================================================
*/

function getShortInfoDisplay(){
    $puri=$_POST["smalluri"]??$_GET["code"];
    $chk=explode(getenv("URI"),$puri);
    if (is_array($chk)&&count($chk)>1)
        $puri=$chk[1];
    if ($puri!=""){
        $uri=checkIfSelfUri($puri);
        if (empty($uri)){
            $db = new database();
            $res=$db->connect();
            if ($res["conn"]){
                $uri_code=str_replace(getenv("URI"),"",$puri);
                $res=$db->getShortlinkInfo($uri_code);
                $content=getShortInfoContent($puri);
                if (empty($res)){
                    $content="<div class='alert alert-danger'>Errore: <strong>uri</strong> non trovato</div>";
                    $content.=getShortInfoContent();
                } else {
                    $date = new DateTime($res["created"]);
                    $locale = 'it_IT';
                    $formatter = new \IntlDateFormatter(
                        $locale,                         // Locale
                        \IntlDateFormatter::FULL,         // Tipo di formattazione della data
                        \IntlDateFormatter::FULL,         // Tipo di formattazione dell'ora
                        'Europe/Rome',                   // Fuso orario
                        \IntlDateFormatter::GREGORIAN,    // Calendario
                        "EEEE dd MMMM yyyy ! HH:mm:ss"      // Pattern di formattazione
                    );
                    $formattedDate = str_replace("!","alle",$formatter->format($date));

                    $rows=getCallsLog($db,$puri);
                    $hret="<table cellpadding=0 cellspacing=0 class='table table-striped table-bordered table-hover'>";
                    $hret.="<tr><th colspan=2>Data</th><th colspan=3>Indirizzo IP</th><th colspan=2>Geolocalizzazione</th></tr><tbody>";
                    if (is_array($rows)){
                        foreach ($rows as $row){
                            $cols=explode(",",$row);
                            $hret.="<tr><td class='tdcont'>".$cols[1]."</td><td class='tdsep'>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td class='tdcont'>".$cols[0]."</td><td class='tdsep'>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td class='tdcont'>".implode(" , ",explode("|",$cols[2]))."</td></tr>";
                        }
                    } 
                    $hret.="</tbody></table>";

                    $content.="<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js'></script>";
                    $content.="<div class='alert alert-info'><table width='100%'><tr><td width=85%>";
                    $content.="<label>Il link originale &egrave;:</label><input type='text' class='input-text' id='originalLink' value='".$res["full_uri"]."' readonly><button class='btn btn-warning' onclick='copyShortLink()'>Copia</button>";
                    $content.="<script>function copyShortLink(){var copyText=document.getElementById('originalLink').value;navigator.clipboard.writeText(copyText).then(function(){alert('Link copiato: '+ copyText);},function(err){console.error('Errore nella copia:', err);});}</script>";
                    $content.="<table style='padding-top:15px' width=100%><tr><td width=65%><label>&Egrave; stato creato il:</label><input type='text' class='input-text' id='createdLink' value='".$formattedDate."' readonly></td><td>&nbsp;</td>";
                    $content.="<td width=35%><label>Ed &egrave; stato richiesto:</label><input type='text' class='input-text' id='createdLink' value='".$res["calls"]." volte' readonly></div></td></tr></table>";
                    $content.="<td width='15%' align='left' style='padding-left:30px'><img id='qrcode' style='border:solid 5px #fff' src='https://api.qrserver.com/v1/create-qr-code/?data=" .urlencode(getenv("URI").$uri_code). "&amp;size=100x100&amp;color=0800A0' alt='' title='qr-code' width='100px' height='100px' /></td></tr></table></div>";
                    $content.='<section class="accordion"><div class="tab"><input type="radio" name="accordion-2" id="rd1"><label for="rd1" class="tab__label">Downloads info</label>';
                    $content.='<div class="tab__content"><p>'.$hret.'</p>';
                    $content.='</div><div class="tab"><input type="radio" name="accordion-2" id="rd3"><label for="rd3" class="tab__close">Chiudi &times;</label></div></div></section>';
                }
            }
        } else {
            $content="<div class='alert alert-danger'>Errore: <strong>uri</strong> non corretto</div>";
            $content.=getShortInfoContent($puri);
        }
    } else {
        $content="<div class='alert alert-danger'>Errore: trascrivere un <strong>uri</strong> del quale ottenere le informazioni!</div>";
        $content.=getShortInfoContent();
    }
    return $content;
}

function getShortLinkDisplay(){
    $uri="";
    $content=getShortenContent($uri);
    try{
        $puri=$_POST["uri"];
        if ($puri!=""){
            $uri=checkIfSelfUri($puri);
            if (empty($uri)){
                $content="<div class='alert alert-danger'>Errore: <strong>uri</strong> non corretto oppure loop-<strong>uri</strong> (non è possibile, ne consigliabile, accorciare un link di <strong>".getenv("URI")."</strong>)</div>";
                $content.=getShortenContent($puri);
            } else {
                $content=getShortenContent($uri);
                $db = new database();
                $res=$db->connect();
                if ($res["conn"]){
                    $shorCode=$db->createShortlink($uri);
                    $content="<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js'></script>";
                    $content.="<div class='alert alert-info'><table width='100%'><tr><td width=50%><label>Il link corto &egrave;</label><input type='text' class='input-text' id='shortLink' value='".getenv("URI").$shorCode."' readonly><button class='btn btn-warning' onclick='copyShortLink()'>Copia</button></td>";
                    $content.="<td width='50%' align='left' style='padding-left:30px'><img id='qrcode' style='border:solid 5px #fff' src='https://api.qrserver.com/v1/create-qr-code/?data=" .urlencode(getenv("URI").$shorCode). "&amp;size=100x100' alt='' title='qr-code' width='100px' height='100px' /></td></tr></table></div>";
                    $content.="<script>function copyShortLink(){var copyText=document.getElementById('shortLink').value;navigator.clipboard.writeText(copyText).then(function(){alert('Link copiato: '+ copyText);},function(err){console.error('Errore nella copia:', err);});}</script>";
                } else {
                    $content="<div class='alert alert-danger'>Errore: ".$res["err"]."</div>".$content;
                }
            }
        } else {
            $content="<div class='alert alert-danger'>Errore: Inserire un link corretto nell'apposito spazio prima di premere \"<strong>Riduci il link</strong>\"</div>".$content;
        }
    } catch (Exception $e){
        $content="<div class='alert alert-danger'>Errore: ".$e->getMessage()."</div>".$content;
    }
    $content.="<div style='margin-top:20px'><button type='button' class='btn btn-warning' onclick=\"window.location.href='/'\">&lt; Home</button>&nbsp;<button type='button' class='btn btn-warning' onclick=\"window.location.href='info'\">Informazioni &gt;</button></div>";
    return $content;
}