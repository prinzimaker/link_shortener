<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License
=====================================================================
This web app needs just Apache, PHP (7.4->8.3) and MySQL to work.
---------------------------------------------------------------------
This class contains all the DB logic for the link shortener
-
v1.4.0 - Aldo Prinzi - 03 Mar 2025
---------
UPDATES
---------
2025.02.25 - file renamed from ._connction.php to ._database.php
2025.02.24 - Added statement prepare extraction (needed by localisation
             database based funtions) 
           - Added shortcode change
           - Added shortcode delete
2024.10.04 - Added variable lenght for short link
2025.02.13 - Modified the way the statistics are stored: if short link 
             is not found, the statistics are not stored
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

    public function getPreparedStatement($sql){
        if (isset($this->pdo))
            return $this->pdo->prepare($sql);
        return null; 
    }
    
    function getFullLink($code){
        if (!isset($this->pdo)) $this->connect();
        try {
            // Start a transaction to ensure data integrity
            $this->pdo->beginTransaction();
            $selectStmt = $this->pdo->prepare("
                SELECT full_uri
                FROM link
                WHERE short_id = :code
                LIMIT 1
            ");
            $selectStmt->execute(['code' => $code]);
            $result = $selectStmt->fetch();
            if ($result["full_uri"]){ 
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
                $this->pdo->commit();
                return ["uri"=>$result["full_uri"],"log"=>$log];
            }
        } catch (PDOException $e) {
            // Roll back the transaction if an error occurs
            $this->pdo->rollBack();
            //this page is not on this site/shortlink/etc
            //raise http 404 error
            http_response_code(404);
            header("HTTP/1.1 404 Not Found");
            exit();
        }
        return null;
    }
    
    function createShortlink($uri,$user_id){
        $existing_short_code=$this->getShortLink($uri, $user_id);
        if (!empty($existing_short_code)) 
            $code=$existing_short_code;
        else {
            $lenght=getenv('LinkLenght');
            if ($lenght<1) $lenght=8;
            $code = $this->_genRndString($lenght);
            if ($code!="")
                $this->putlink($code, $uri, $user_id);
            else
                $code="Error!";
        }
        return $code;
    }

    function getShortLink($uri,$user_id){
        if (!isset($this->pdo)) $this->connect();
        $qry="SELECT short_id FROM link WHERE sha_uri = :shuri";
        $params=['shuri' => hash("sha512", $user_id."!".$uri)];
        if ($user_id!=""){
            $qry.=" AND cust_id = :cust_id";
            $params['cust_id']=$user_id;
        }
        $stmt = $this->pdo->prepare($qry);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result["short_id"];
    }

    function getShortCodeData($shortCode){
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare("SELECT * FROM link WHERE short_id = :shortCode");
        $stmt->execute(['shortCode' => $shortCode]);
        return $stmt->fetch();
    }

    function changeShortCode($oldCode,$newCode,$newUri,$user_id){
        if (!isset($this->pdo)) $this->connect();
        $exists=$this->getShortCodeData($newCode);
        if ($exists) 
            return "AE";
        $stmt = $this->pdo->prepare("UPDATE link SET short_id = :newcode, sha_uri= :shuri WHERE short_id = :oldcode");
        if ($stmt->execute(['newcode' => $newCode, 'oldcode' => $oldCode, 'shuri' => hash("sha512", $user_id."!".$newUri)]))
            return "DO";
        return "ER";
    }

    function deleteShortCodeData($delCode){
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare("DELETE FROM calls WHERE short_id = :delCode");
        if ($stmt->execute(['delCode' => $delCode])){
            $stmt = $this->pdo->prepare("DELETE FROM link WHERE short_id = :delCode");
            if ($stmt->execute(['delCode' => $delCode]))
                return true;
        }
        return false;
    }

    function putlink($linkcode, $uri, $user_id){
        if (!isset($this->pdo)) $this->connect();
        /* The line `         = ->pdo->prepare("INSERT INTO link (short_id, full_uri,
        user_id, sha_uri, cust_id) VALUES (:code, :uri, :shuri, 0)");` is preparing an SQL statement
        to insert a new record into the `link` table in the database. */
        $stmt = $this->pdo->prepare("INSERT INTO link (short_id, full_uri, cust_id, sha_uri) VALUES (:code, :uri, :cust_id, :shuri)");
        $stmt->execute(['code' => $linkcode, 'uri' => $uri, 'cust_id'=>$user_id , 'shuri' => hash("sha512", $user_id."!".$uri)]);
    }

    function getShortlinkInfo($short_id, $cust_id){
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare("SELECT * FROM link WHERE short_id = :short_id and cust_id= :cust_id LIMIT 1");
        $stmt->execute(['short_id' => $short_id,'cust_id' => $cust_id]);
        $result = $stmt->fetch();
        return $result;
    }
    function getUserShortDatatable(){
        $result=[];
        $userData="";
        if (isset($_SESSION["user"]))
            $userData=$_SESSION["user"];
        if (!empty($userData)){
            if (!isset($this->pdo)) $this->connect();
            $stmt = $this->pdo->prepare("SELECT * FROM link WHERE cust_id = :cust_id");
            $stmt->execute(['cust_id' => $userData["cust_id"]]);
            $result = $stmt->fetchAll();
        }
        return $result;
    }

    function getDownloadInfo($short_id,$cust_id){
        $ret="";
        $shortInfo=$this->getShortlinkInfo($short_id, $cust_id);
        if ($shortInfo!==false){
            if (!isset($this->pdo)) $this->connect();
            $stmt = $this->pdo->prepare("SELECT * FROM calls WHERE short_id = :short_id LIMIT 1");
            $stmt->execute(['short_id' => $short_id]);
            $result = $stmt->fetch();
            if (isset($result["call_log"])){
                $ret=explode(";",$result["call_log"]);
            }
        }
        return $ret;
    }

    public function getUserData($email, $allData=false,$selectBy=0) {
        $query = "SELECT cust_id, descr, apikey, pass, active, is_admin, max_links";
        if ($allData)
            $query .= ", email, email_verified, email_verif_code";
        $query .= " FROM customers WHERE";
        if ($selectBy==0)
            $query .=" email = :field";
        else
            $query .=" apikey = :field";
        $params = [':field' => $email];
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result;
    }

    public function getUserByApiKey($apiKey, $allData=true) {
        return $this->getUserData($apiKey, $allData,1);
    }

    public function createUser($descr,$email,$passHash,$apiKey,$verificationCode) {
        $query = "INSERT INTO customers (descr, email, pass, apikey, email_verif_code, email_verified, active, is_admin, max_links)
        VALUES (:descr, :email, :pass, :apikey, :verif_code, 0, 0, 0, 10)";
        $params = [
        ':descr'      => $descr,
        ':email'      => $email,
        ':pass'       => $passHash,
        ':apikey'     => $apiKey,
        ':verif_code' => $verificationCode
        ];
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($params);
    }

    public function updateUserData($custId, $descr, $email) {
        $query = "UPDATE customers SET descr = :descr, email = :email WHERE cust_id = :cust_id";
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':descr'   => $descr,
            ':email'   => $email,
            ':cust_id' => $custId
        ]);
    }
    public function updateUserPassword($custId,$passHash) {
        $query = "UPDATE customers SET pass = :passHash WHERE cust_id = :cust_id";
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':pass'   => $passHash,
            ':cust_id' => $custId
        ]);
    }
    public function changePassword($email, $oldPassword, $newPassword) {
        // Recupera l'hash della password corrente (campo "pass")
        $query = "SELECT pass FROM customers WHERE email = :email limit 1";
        
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        
        // Se la vecchia password è corretta, aggiorna con quella nuova
        if ($user && password_verify($oldPassword, $user['pass'])) {
            $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
            $updateQuery = "UPDATE customers SET pass = :newPass WHERE email = :email";
            $stmt = $this->pdo->prepare($updateQuery);
            return $stmt->execute([
                ':newPass' => $newHash,
                ':email'   => $email
            ]);
        }
        return false;
    }
    public function verifyUserCode($emailCode){
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare("SELECT cust_id FROM customers WHERE email_verif_code = :code");
        $stmt->execute([':code' => $emailCode]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $updateStmt = $this->pdo->prepare("UPDATE customers SET email_verified = 1, email_verif_code = NULL, active = 1 WHERE cust_id = :id");
            return $updateStmt->execute([':id' => $user['cust_id']]);
        }
        return false;
    }
 
    public function getApiKeyInfo($apiKey) {
        $query = "SELECT cust_id,api_key_active FROM customers WHERE api_key = :api_key";
        $params = [':api_key' => $apiKey];
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($result))
            return ["apiKey"=>$apiKey,"cust_id"=>$result["cust_id"],"isActive"=>$result["api_key_active"]];
        return false;
    }

    function getStatistics($short_id){
        $query = "
        SELECT 
            DATE(call_log) AS day,
            CASE 
                WHEN TIME(call_log) BETWEEN '00:00:00' AND '06:30:00' THEN 1
                WHEN TIME(call_log) BETWEEN '06:31:00' AND '20:30:00' THEN 2
                WHEN TIME(call_log) BETWEEN '20:31:00' AND '23:59:59' THEN 3
            END AS time_part,
            COUNT(*) AS call_count
        FROM calls 
        WHERE short_id = :code
        GROUP BY day, time_part
        ORDER BY day, time_part
        ";
        if (!isset($this->pdo)) $this->connect();
        $stmt = $this->pdo->prepare($query);
        $params = [':code' => $short_id];
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    function createAndCheckNewApiKey(){
        if (!isset($this->pdo)) $this->connect();
        $newApiKey="";
        $stmt = $this->pdo->prepare("select cust_id from customers where apikey = :newApiKey");
        $i=0;
        do{
            srand((int)(microtime(true) * 1000000)); // converte i microsecondi in un intero
            $newApiKey ="";
            for ($i = 0; $i < 24; $i++) {
                $newApiKey .= bin2hex(chr(mt_rand(0, 254)));
            }
            $stmt->execute(['newApiKey' => $newApiKey]);
            $exists = $stmt->fetchColumn();
            if (!$exists)
                break;
            if ($i++>200) break;
        } while (true);
        return $newApiKey;
    }

    function checkAndAssignNewApiKey($oldApiKey,$custid=""){
        $newApiKey=$this->createAndCheckNewApiKey();
        $stmt = $this->pdo->prepare("UPDATE customers SET apikey = :newApiKey WHERE ".($custid==""?"apikey = :field":"cust_id = :field"));
        if ($custid!="")
            $oldApiKey=$custid;
        if (!$stmt->execute(['newApiKey' => $newApiKey, 'field' => $oldApiKey]))
            $newApiKey="";
        sleep(2);
        return $newApiKey;
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
