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
v1.4.1 - Aldo Prinzi - 07 Mar 2025

=====================================================================

NEED TO BE IMPLEMENTED!

*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php'; 

class UserManager {
    private $_db;

    public function __construct() {
        $this->_db = new Database(); 
    }

    public function getUserData($email,$allData=false) {
        return $this->_db->getUserData($email,$allData); 
    }

    public function authenticate($email,$password) {
        $user=new SLUsers();
        $email=$this->normalizeEmail($email);
        $user->load($email,$password);
        return $user->getData();
    }

    public function checkUserApi($apiKey) {
        $data=$this->_db->getUserByApiKey($apiKey);
        return (!empty($data) && $data['active']>0);
    }
    public function isApiKeyActive($apiKey) {
        $res=$this->_db->getApiKeyInfo($apiKey);
        if (!$res===false){
            return $res["isActive"];
        }
        return false;
    }

    function normalizeEmail($email) {
        // 1. Converti l'email in minuscolo
        $email = strtolower($email);
        
        // 2. Verifica se l'email termina con "gmail.com"
        // Utilizziamo explode per separare la parte locale dal dominio
        $parts = explode('@', $email, 2);
        if (count($parts) < 2) {
            // Se l'email non contiene "@", la ritorna così com'è
            return $email;
        }
        list($local, $domain) = $parts;
        
        // Se il dominio è "gmail.com" (o eventualmente "google.com" se serve)
        if ($domain === "gmail.com") {
            // 3. Rimuovi i puntini dalla parte locale
            $local = str_replace('.', '', $local);
        }
        
        return $local . '@' . $domain;
    }
    
    public function manageForgotPassword($extEmail="",$changeRequest=false) {
        $userData=[];
        if (isset($_SESSION["user"]))
            $userData=$_SESSION["user"];
        if (!$changeRequest && $extEmail!=""){
            $extEmail=$this->normalizeEmail($extEmail);
            $userData=$this->getUserData($extEmail);
            if ($userData!==false){
                $mmng=new MailManager();
                $vrfyCode=$this->generateVerificationCode(6)."-".$this->generateVerificationCode(8).".".$this->generateVerificationCode(4);
                if ($mmng->sendUserChangePassEmail($extEmail,$userData["descr"],$vrfyCode)){
                    $US=new SLUsers($extEmail);
                    $vrfyCode= preg_replace('/[^A-Z0-9]/', '', $vrfyCode);
                    $US->updateUserVerifyCode($vrfyCode);
                    return str_replace("{{email}}",$extEmail,lng("change_pass_msg"));
                }
                return "<h2>Unknown Error!</h2>";
            }
        } else {
            if ($changeRequest) {
                $vrfyCode=$this->generateVerificationCode($length = 30);
                //$_SESSION["state"]="MFP";
                $US=new SLUsers($extEmail);
                $vrfyCode2= preg_replace('/[^A-Z0-9]/', '', $vrfyCode);
                $_SESSION["verifycode"]=$vrfyCode2;
                $US->updateUserVerifyCode($vrfyCode2);
                $userData=$US->getData();
                return getForgotPasswordForm($userData["descr"],$vrfyCode);
            } else {
                return "<h2>User not logged in!</h2>";
            }
        }
    }

    function handleChangePass(){
        $userNewPass=trim(isset($_POST["password"])?$_POST["password"]:"");
        if (isset($_SESSION["user"]))
            $userData=$_SESSION["user"];
        if (isset($userData["cust_id"]) && $userData["cust_id"]>0 && isset($_POST["password_confirm"]))
            $vrfy=(""==str_replace($userNewPass,"",$_POST["password_confirm"]))?"OK":"";
        else
            $vrfy=preg_replace('/[^A-Z0-9]/', '', isset($_POST["verifycode"])?$_POST["verifycode"]:"");
        if ($userNewPass=="" || $vrfy==""){
            return false;
        }
        $US=new SLUsers();
        if ($vrfy=="OK"){
            return $US->updatePassword($userData["cust_id"],$userNewPass);
        } else {
            return $US->changePassword($vrfy,$userNewPass);
        }
    }
    public function registerUser($email, $password, $descr = '') {
        $user=new SLUsers();
        $verificationCode=$this->generateVerificationCode();
        $this->sendVerificationEmail($email, $verificationCode);
        $user->createNew($email, $password, $descr,$verificationCode);
        return true;
    }

    public function sendVerificationEmail($email,$verificationCode="") {
        $mmng=new MailManager();
        if (empty($verificationCode))
            $verificationCode=$this->generateVerificationCode(6)."-".$this->generateVerificationCode(8).".".$this->generateVerificationCode(4);
        return $mmng->sendUserVerificationEmail($email,$verificationCode);
    }

    function generateVerificationCode($length = 6) {
        $allowedChars = 'ACDFGHJKLMNPRSTUVWXYZ1245679';
        $code = '';
        $maxIndex = strlen($allowedChars) - 1;
        for ($i = 0; $i < $length; $i++) {
            // Utilizza random_int per una maggiore sicurezza
            $code .= $allowedChars[random_int(0, $maxIndex)];
        }
        return $code;
    }

    public function verifyEmail($code) {
        $user=new SLUsers();
        return $user->verifyEmailCode($code)[0]>0;
    }
    public function verifyPassLost($code) {
        $user=new SLUsers();
        return $user->verifyEmailCode($code);
    }

    public function userLinkLimit($user_id){
        $nxl=intval($this->_db->getCountLink($user_id));
        $user=new SLUsers($user_id);
        if ($user->getData()["is_admin"]>0)
            $mxl=99999;
        else
            $mxl=intval($user->getData()["max_links"]);
        return ($nxl+1)>=$mxl;
    }

    /*
    public function changePassword($email, $oldPassword, $newPassword) {
        $user=new SLUsers();
        return $user->changePassword($email, $newPassword);
        /*        
        // Recupera la password memorizzata (campo "pass")
        $query = "SELECT pass FROM customers WHERE email = :email";
        $params = [':email' => $email];

        $stmt = $this->_db->connect()->prepare($query);
        $stmt->execute($params);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($oldPassword, $user['pass'])) {
            $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $updateQuery = "UPDATE customers SET pass = :pass WHERE email = :email";
            $updateParams = [
                ':pass'  => $hashedNewPassword,
                ':email' => $email
            ];

            $updateStmt = $this->_db->connect()->prepare($updateQuery);
            return $updateStmt->execute($updateParams);
        }
        return false;
        
    }*/
}


