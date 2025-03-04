<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License
=====================================================================
This web app needs just Apache, PHP (7.4->8.3) and MySQL to work.
---------------------------------------------------------------------
This class contains all the Users-Management class/functions
-
v1.4.0 - Aldo Prinzi - 03 Mar 2025

=====================================================================
*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php'; 

class MailManager {
    private $_db;

    public function __construct() {
        $this->_db = new Database(); 
    }

    private function _prepareMailSend($debug=false) {
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = $debug;
        try {
            $mailUser=getenv("mailuser");
            $mail->isSMTP();
            $mail->Host       = getenv("mailhost"); // Es. smtp.example.com
            $mail->SMTPAuth   = true;
            $mail->Username   = $mailUser;
            $mail->Password   = getenv("mailpass");
            if (getenv("mailencription")=="STARTTLS")
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            if (getenv("mailencription")=="SMTPS")
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = getenv("mailport");

            $fromName=empty(getenv("mailfrom"))?"Link Shortener":getenv("mailfrom");
            $mail->setFrom($mailUser, $fromName);
        } catch (Exception $e) {
            error_log("Errore nell'invio dell'email: {$mail->ErrorInfo}");
            return false;
        }
        return $mail;
    }

    public function sendUserVerificationEmail($email,$verificationCode) {
        $mail = $this->_prepareMailSend();
        if ($mail!==false)
        {
            $sitename=rtrim(str_replace(["http://","https://"],["",""],getenv("URI")),"/");
            $vLink = getenv("URI") . "/_pls_fnc_user?verify=" . $verificationCode;
            $link="<a href='".$vLink."'>".$vLink."</a>";

            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = $sitename." - ".lng("subjverifyemail ");
            $mail->Body =str_replace("{{link}}",$link,lng("check-email-body"));
            return $mail->send();
        } 
        return false;
    }

    public function sendUserChangePassEmail($email,$userDescr,$verificationCode){
        $mail = $this->_prepareMailSend();
        if ($mail!==false)
        {
            $sitename=rtrim(str_replace(["http://","https://"],["",""],getenv("URI")),"/");
            $vLink = getenv("URI") . "/_pls_fnc_fgtpass?verify=" . $verificationCode;
            $link="<a href='".$vLink."'>".$vLink."</a>";

            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = $sitename." - ".lng("subjchangepass");
            $mail->Body =str_replace(["{{link}}","{{username}}"],[$link,$userDescr],lng("chngpass-email-body"));
            return $mail->send();
        } 
        return false;
    }
}


