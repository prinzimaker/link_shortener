<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License
=====================================================================
This web app needs just Apache, PHP (7.4->8.3) and MySQL to work.
---------------------------------------------------------------------
This class contains all the USER class/functions
-
v1.4.0 - Aldo Prinzi - 03 Mar 2025
=====================================================================

NEED TO BE IMPLEMENTED!

*/

class SLUsers {
    private $_mng;
    private $_logged = false;
    
    public function __construct($userId = "", $password = "") {
        $db = new Database();
        $this->_mng = new UserManager(); // Potresti passarvi il database se necessario
        if ($userId != "" && $password != "")
            $this->load($userId, $password);
    }
    
    public function load($userId, $password = "") {
        $data = $this->_mng->getUserData($userId, true);
        $this->_logged = true;
        if ($data && $data !== false) {
            if ($password != "")
                $this->_logged = $this->_password_verify($password, $data['email'], $data['pass']);
        } else {
            $this->_logged = false;
        }
        if ($this->_logged)
            $_SESSION["user"] = $data;
        else 
            $_SESSION["user"] = "";
        
        if (!$this->_logged)
            $_SESSION["loginerr"] = "invalid_uid_or_pass";
    }
    
    public function isLogged() {
        return $this->_logged;
    }
    
    public function register($email, $password) {
        return $this->_mng->registerUser($email, $password);
    }
    
    // Funzione per aggiornare i dati dell'utente (nome/descrizione ed email)
    public function updateUserData($custId, $descr, $email) {
        $db = new Database();
        return $db->updateUserData($custId, $descr, $email);
    }
    
    // Funzione per il cambio password: verifica la vecchia password e, se corretta, aggiorna con quella nuova
    public function changePassword($email, $newPassword) {
        $db = new Database();
        if ($this->_logged){
            $userData = "";
            if (isset($_SESSION["user"]))
                $userData = $_SESSION["user"];
            if (empty($userData)){
                $this->_logged = false;
            } else {
                $newHash=$this->_createUserHash($email, $newPassword);
                return $db->updateUserPassword($userData["cust_id"],$newHash);
            }
        }
        return false;
    }
    
    public function sendVerificationEmail($email) {
        return $this->_mng->sendVerificationEmail($email);
    }
    
    public function verifyEmailCode($emailCode) {
        $db = new Database();
        return $db->verifyUserCode($emailCode);
    }
    
    private function _password_verify($password, $email, $hash) {
        if ($hash == "")
            return true;
        else {
            return $hash==$this->_createUserHash($email, $password);
        }
    }
    
    public function createNew($email, $password, $descr, $verificationCode="") {
        $db = new Database();
        $passHash = $this->_createUserHash($email, $password);
        $newApiKey=$db->createAndCheckNewApiKey();
        return $db->createUser($descr,$email,$passHash,$newApiKey,$verificationCode);
    }

    private function _createUserHash($email, $strPass) {
        // Esempio con SHA-256, se necessario
        $algorithm = 'sha256';
        $strHPass = hash_hmac($algorithm, $email, $strPass);
        return $strHPass;
    }
    
    public function assignNewApiKey() {
        $userData = "";
        if (isset($_SESSION["user"]))
            $userData = $_SESSION["user"];
        if (empty($userData))
            return;
        $db = new Database();
        $apiKey = $db->checkAndAssignNewApiKey($userData["apikey"]);
        if ($apiKey != "")
            $_SESSION["user"]["apikey"] = $apiKey;
        else
            $apiKey = $userData["apikey"];
        return $apiKey;
    }
}








/*
class SLUsers{
    private $_mng;
    private $_logged=false;
    public function __construct($userId="",$password="") {
        $db=new Database();
        $this->_mng=new UserManager($db);
        if ($userId!="" && $password!="")
            $this->load($userId,$password);
    }
    public function logout(){
        if (isset($_SESSION["user"]))
            unset($_SESSION["user"]);
    }
    public function load ($userId,$password=""){
        $data=$this->_mng->getUserData($userId,true);
        $this->_logged=true;
        if ($data && $data!==false){
            if ($password!="")
                $this->_logged=$this->_password_verify($password, $data['email'],$data['pass']);
        } else
            $this->_logged=false;
        if ($this->_logged)
            $_SESSION["user"]=$data;
        else 
            $_SESSION["user"]="";
        
        if (!$this->_logged)
            $_SESSION["loginerr"]="invalid_uid_or_pass";
    }

    public function isLogged(){
        return $this->_logged;
    }

    public function register($email,$password){
        return $this->_mng->registerUser($email,$password);
    }

    public function changePassword($email,$oldPassword,$newPassword){
        return $this->_mng->changePassword($email,$oldPassword,$newPassword);
    }

    public function sendVerificationEmail($email){
        return $this->_mng->sendVerificationEmail($email);
    }

    public function verifyEmail($code){
        return $this->_mng->verifyEmail($code);
    }

    private function _password_verify($password,$email, $hash){
        if ($hash=="")
            return true;
        else {
            return $hash=$this->_createUserHash($email, $password);
        }
    }
    private function _createUserHash($email, $strPass) {
        // L'algoritmo di hashing che useremo è SHA-256, ma puoi scegliere altri algoritmi supportati da hash_algos()
        $algorithm = 'sha256';
        // Creiamo un hash usando l'email come messaggio e la password come chiave
        $strHPass = hash_hmac($algorithm, $email, $strPass);
        return $strHPass;
    }

    function assignNewApiKey(){
        $userData="";
        if (isset($_SESSION["user"]))
            $userData=$_SESSION["user"];
        if (empty($userData))
            return;
        $db=new Database();
        $apiKey=$db->checkAndAssignNewApiKey($userData["apikey"]);
        if ($apiKey!="")
            $_SESSION["user"]["apikey"]=$apiKey;
        else
            $apiKey=$userData["apikey"];
        return $apiKey;
    }
}
*/