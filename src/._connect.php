<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License
=====================================================================
This web app needs just Apache, PHP (74->8.3) and MySQL to work.
---------------------------------------------------------------------
This class contains all the DB logic for the link shortener
-
v1.2.1 - Aldo Prinzi - 30 Dic 2024
---------
UPDATES
---------
2024.10.04 - Added variable lenght for short link
=====================================================================
*/

class Database {
    private $host;
    private $pdo;
    private $db;
    private $user;
    private $pass;
    private $options;
    private $charset = 'utf8mb4';

    function __construct() {
        $this->host = getenv('DB_HOST');
        $this->db   = getenv('DB_NAME');
        $this->user = getenv('DB_USER');
        $this->pass = getenv('DB_PASS');
        $this->options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
    }

// ----------------------------------------------------
// Database functions
// ----------------------------------------------------
    function connect(){
        $dsn = "mysql:host=".$this->host.";dbname=".$this->db.";charset=".$this->charset;
        $ret["conn"]=false;
        $ret["err"]="";
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $this->options);
            $ret["conn"]=true;
        } catch (PDOException $e) {
            $ret["err"]=$e->getMessage();
        }
        return $ret;
    }
    
    function getFullLink($code){
        if (!isset($this->pdo)) $this->connect();
        try {
            // Start a transaction to ensure data integrity
            $this->pdo->beginTransaction();
            $updateStmt1 = $this->pdo->prepare("
                UPDATE link
                SET calls = calls + 1, last_call = NOW()
                WHERE short_id = :code
            ");
            $updateStmt1->execute(['code' => $code]);
            // CALLS LOG UPDATE
            $log=$_SERVER['REMOTE_ADDR'].",".date("Y-m-d H:i:s").";";
            $updateStmt2 = $this->pdo->prepare("
                INSERT INTO calls (short_id, call_log) VALUES(:code, :log) 
                ON DUPLICATE KEY UPDATE call_log = CONCAT(call_log, :log2)
            ");
            $updateStmt2->execute(['code' => $code, 'log' => $log, 'log2' => $log]);
            $selectStmt = $this->pdo->prepare("
                SELECT full_uri
                FROM link
                WHERE short_id = :code
                LIMIT 1
            ");
            $selectStmt->execute(['code' => $code]);
            $result = $selectStmt->fetch();
            $this->pdo->commit();
            return $result["full_uri"];
        } catch (PDOException $e) {
            // Roll back the transaction if an error occurs
            $this->pdo->rollBack();
            //this page is not on this site/shortlink/etc
            //raise http 404 error
            http_response_code(404);
            header("HTTP/1.1 404 Not Found");
            exit();
        }
    }
    
    function createShortlink($uri){
        $existing_short_code=$this->getShortCode($uri);
        if (!empty($existing_short_code)) 
            $code=$existing_short_code;
        else {
            $lenght=getenv('LinkLenght');
            if ($lenght<1) $lenght=8;
            $code = $this->_genRndString($lenght);
            if ($code!="")
                $this->putlink($code, $uri);
            else
                $code="Error!";
        }
        return $code;
    }

    function getShortCode($uri){
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare("SELECT short_id FROM link WHERE sha_uri = :shuri LIMIT 1");
        $stmt->execute(['shuri' => hash("sha512", $uri)]);
        $result = $stmt->fetch();
        return $result["short_id"];
    }

    function putlink($linkcode, $uri){
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare("INSERT INTO link (short_id, full_uri, sha_uri, cust_id) VALUES (:code, :uri, :shuri, 0)");
        $stmt->execute(['code' => $linkcode, 'uri' => $uri, 'shuri' => hash("sha512", $uri)]);
    }

    function getShortlinkInfo($short_id){
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare("SELECT * FROM link WHERE short_id = :short_id LIMIT 1");
        $stmt->execute(['short_id' => $short_id]);
        $result = $stmt->fetch();
        return $result;
    }

    function getDownloadInfo($short_id){
        $ret="";
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare("SELECT * FROM calls WHERE short_id = :short_id LIMIT 1");
        $stmt->execute(['short_id' => $short_id]);
        $result = $stmt->fetch();
        if (isset($result["call_log"])){
            $ret=explode(";",$result["call_log"]);
        }
        return $ret;
    }

    public function getUserData($email) {
        $query = "SELECT descr, apikey, pass, active, is_admin, max_links FROM customers WHERE email = :email";
        $params = [':email' => $email];
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result;
    }

    public function getUserByApiKey($apiKey) {
        $query = "SELECT descr, apikey, pass, active, is_admin, max_links FROM customers WHERE apikey = :apikey";
        $params = [':apikey' => $apiKey];
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result;
    }


// ----------------------------------------------------
// Other private functions
// ----------------------------------------------------

    // genera una stringa di max 8 caratteri random e verifica che non ce ne siano di uguali nel database
    private function _genRndString($length = 8) {
        $allCharacters = 'ACDEFGHJKLMNPQRSTUVWXYZ' . '2345679' .'abcdefghijkmnpqrstuvwxyz';
        // Seed the random number generator
        $milliseconds = (int) (microtime(true) * 1000);
        srand($milliseconds);
        $max=100;
        do{
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $allCharacters[rand(0, strlen($allCharacters) - 1)];
            }
            // Shuffle the string to randomize character positions
            $randomString = str_shuffle($randomString);
            // loop secure
            if ($max--<1){
                $randomString="LOOP ERROR!";
                break;
            }
        } while ($this->_checkCode($randomString));
        return $randomString;
    }
    private function _checkCode($randomCode){
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare("SELECT 1 FROM link WHERE short_id = :code LIMIT 1");
        $stmt->execute(['code' => $randomCode]);
        $exists = $stmt->fetchColumn();
        return $exists ? true : false;
    }
    private function _callsTableExists(){
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare("SELECT count(*) FROM information_schema.TABLES WHERE (TABLE_SCHEMA = 'shortlinks') AND (TABLE_NAME = 'calls')");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result>0;
    }
    private function _callsTableCreate(){
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare("create table calls{short_id varchar(10) not null, call_log longtext, PRIMARY KEY (short_id),} DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        $stmt->execute();
        $stmt = $this->pdo->prepare("create table calls{short_id varchar(10) not null, call_log longtext, PRIMARY KEY (short_id),} DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        $stmt->execute();
        return $stmt->fetch();
    }
}
