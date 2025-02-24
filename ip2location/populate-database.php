<?php
/* 
=====================================================================
      Quick and Dirty Prinzimaker's Link Shortener
      Copyright (C) 2024/2025 - Aldo Prinzi
      Open source project - under MIT License
=====================================================================
This web app needs just Apache, PHP (74->8.3) and MySQL to work.
---------------------------------------------------------------------
This file is a one shot utility to upload ip2location table into the
local database.
1) Go to https://ip2location.com and download the free IPv4 CSV file
2) Place the CSV file in the same folder of this script
3) Run this script from the command line
=====================================================================
*/
echo "Inserting IP localization data into database:\n";

// Necessary including.
include '../src/._loadenv.php';
include '../src/._connect.php';

// CSV file name and location
$csvFile = 'IP2LOCATION-LITE-DB11.CSV';
if (!file_exists($csvFile)) {
    die("CSV file not found: " . $csvFile."\nCheck the author's website for the file - https://ip2location.com");
}
$handle = fopen($csvFile, 'r');
if (!$handle) {
    die("Cannot open the CSV file: " . $csvFile);
}

// Preparing SQL insert query 
$sql = "INSERT INTO ip2location (ip_from, ip_to, state_id, state_txt, region, city, geo_lat, geo_long, pobox, timezone)
        VALUES (:ip_from, :ip_to, :state_id, :state_txt, :region, :city, :geo_lat, :geo_long, :pobox, :timezone)";

$inserted = 0;
$db = new Database();
$res=$db->connect();
if ($res["conn"]==false){
    die("Database connection error: " . $res["err"]);
}
$stmt = $db->getPreparedStatement($sql);

// Read the CSV file row by row
while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
    // Check for valid rows (not valid if less than di 10 fields)
    if (count($data) < 10) {
        continue;
    }

    // fgetcsv handles the data removing " quotes if useed

    $ip_from   = (int) $data[0];
    $ip_to     = (int) $data[1];
    $state_id  = $data[2];
    $state_txt = $data[3];
    $region    = $data[4];
    $city      = $data[5];
    $geo_lat  = (float) $data[6];
    $geo_long = (float) $data[7];
    $pobox    = $data[8];
    $timezone = $data[9];

    // PDO's Parameters Bind
    $stmt->bindParam(':ip_from', $ip_from, PDO::PARAM_INT);
    $stmt->bindParam(':ip_to', $ip_to, PDO::PARAM_INT);
    $stmt->bindParam(':state_id', $state_id, PDO::PARAM_STR);
    $stmt->bindParam(':state_txt', $state_txt, PDO::PARAM_STR);
    $stmt->bindParam(':region', $region, PDO::PARAM_STR);
    $stmt->bindParam(':city', $city, PDO::PARAM_STR);
    $stmt->bindParam(':geo_lat', $geo_lat, PDO::PARAM_INT);
    $stmt->bindParam(':geo_long', $geo_long, PDO::PARAM_INT);
    $stmt->bindParam(':pobox', $pobox, PDO::PARAM_STR);
    $stmt->bindParam(':timezone', $timezone, PDO::PARAM_STR);

    try {
        $stmt->execute();
        $inserted++;
    } catch (PDOException $e) {
        // In caso di errore, stampa il messaggio e continua
        echo "Error on row insert: " . $e->getMessage() . "\n";
    }
}

fclose($handle);

echo "Succesfully inserted $inserted rows.\n";
