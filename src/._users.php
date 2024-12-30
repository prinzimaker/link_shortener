<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License
=====================================================================
This web app needs just Apache, PHP (74->8.3) and MySQL to work.
---------------------------------------------------------------------
This class contains all the USER class/functions
-
v1.2.1 - Aldo Prinzi - 30 Dic 2024
=====================================================================

NEED TO BE IMPLEMENTED!

*/
include '../../src/._connect.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; 

class UserManager {
    private $db;

    public function __construct($db) {
        $this->db = $db; 
    }

    public function registerUser($email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $apiKey = bin2hex(random_bytes(32)); // Genera una chiave API univoca

        $query = "INSERT INTO users (email, password, api_key) 
                  VALUES (:email, :password, :api_key)";
        $params = [
            ':email' => $email,
            ':password' => $hashedPassword,
            ':api_key' => $apiKey
        ];

        $res = $this->db->connect()->prepare($query);
        return $res->execute($params);
    }

    public function changePassword($email, $oldPassword, $newPassword) {
        $query = "SELECT password FROM users WHERE email = :email";
        $params = [':email' => $email];

        $stmt = $this->db->connect()->prepare($query);
        $stmt->execute($params);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($oldPassword, $user['password'])) {
            $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $updateQuery = "UPDATE users SET password = :password WHERE email = :email";
            $updateParams = [
                ':password' => $hashedNewPassword,
                ':email' => $email
            ];

            $updateStmt = $this->db->connect()->prepare($updateQuery);
            return $updateStmt->execute($updateParams);
        }
        return false;
    }

    public function getUserData($email) {
        $query = "SELECT email, api_key, api_key_active, created_at 
                  FROM users 
                  WHERE email = :email";
        $params = [':email' => $email];

        $stmt = $this->db->connect()->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isApiKeyActive($apiKey) {
        $query = "SELECT api_key_active FROM users WHERE api_key = :api_key";
        $params = [':api_key' => $apiKey];

        $stmt = $this->db->connect()->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result['api_key_active'];
    }

    public function sendVerificationEmail($email) {
        // Genera un codice univoco
        $verificationCode = bin2hex(random_bytes(32));
        
        // Salva il codice nel database
        $stmt = $this->db->prepare("UPDATE users SET email_verification_code = :code WHERE email = :email");
        $stmt->execute([
            ':code' => $verificationCode,
            ':email' => $email
        ]);

        if ($stmt->rowCount() === 0) {
            return false; // L'email non è stata trovata nel database
        }

        // Configura PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = getenv("mailhost"); // Modifica con il tuo server SMTP
            $mail->SMTPAuth = true;
            $mail->Username = getenv("mailuser"); // Tua email SMTP
            $mail->Password = getenv("mailpass");; // Tua password SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = getenv("mailport");;

            $mail->setFrom(getenv("mailuser"), 'Q&D link shrinker');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Verifica il tuo indirizzo email';
            $verificationLink = getenv("URI")."/user?verify=".$verificationCode;

            $mail->Body = "
                <h1>Verifica la tua Email</h1>
                <p>Grazie, nel ringraziarla per la registrazione, la preghiamo di cliccare sul seguente link per verificare l'indirizzo e-mail:</p>
                <a href='$verificationLink'>Verifica Email</a>
                <p>Se non ha richiesto questa email, ignori questo messaggio.</p>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Errore nell'invio dell'email: {$mail->ErrorInfo}");
            return false;
        }
    }
    
    /**
     * Verifica il codice di verifica dell'utente.
     * @param string $code Il codice di verifica ricevuto.
     * @return bool
     */
    public function verifyEmail($code) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email_verification_code = :code");
        $stmt->execute([':code' => $code]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $updateStmt = $this->db->prepare("UPDATE users SET email_verified = TRUE, email_verification_code = NULL WHERE id = :id");
            return $updateStmt->execute([':id' => $user['id']]);
        }

        return false;
    }
}

