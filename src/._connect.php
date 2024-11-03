<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shoertener
      Copyright (C) 2024 - Aldo Prinzi
      Open source project - under MIT License
=====================================================================
This web app needs just Apache, PHP (74->8.3) and MySQL to work.
---------------------------------------------------------------------
This class contains all the DB logic for the link shortener
-
v0.1.0 - Aldo Prinzi - 03 Nov 2024
=====================================================================
*/

class database {
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
            $updateStmt = $this->pdo->prepare("
                UPDATE link
                SET calls = calls + 1, last_call = NOW()
                WHERE short_id = :code
            ");
            $updateStmt->execute(['code' => $code]);
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
            throw $e; // Re-throw the exception or handle it as needed
        }
    }
    
    function createShortlink($uri){
        $existing_short_code=$this->getShortCode($uri);
        if (!empty($existing_short_code)) 
            $code=$existing_short_code;
        else {
            $code = $this->_genRndString();
            if ($code!="")
                $this->putlink($code, $uri);
            else
                $code="Error!";
        }
        return $code;
    }

    function getShortCode($uri){
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare("SELECT short_id FROM link WHERE full_uri = :uri LIMIT 1");
        $stmt->execute(['uri' => $uri]);
        $result = $stmt->fetch();
        return $result["short_id"];
    }

    function putlink($linkcode, $uri){
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare("INSERT INTO link (short_id, full_uri, cust_id) VALUES (:code, :uri, 0)");
        $stmt->execute(['code' => $linkcode, 'uri' => $uri]);
    }

    function getShortlinkInfo($short_id){
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare("SELECT * FROM link WHERE short_id = :short_id LIMIT 1");
        $stmt->execute(['short_id' => $short_id]);
        $result = $stmt->fetch();
        return $result;
    }

// ----------------------------------------------------
// Other private functions
// ----------------------------------------------------

    // genera una stringa di massimo 8 caratteri random e verifica che non ce ne siano di uguali nel database
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
                $randomString="";
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
}