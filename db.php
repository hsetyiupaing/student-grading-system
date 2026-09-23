<?php
// db.php - Database connection

$env = parse_ini_file(__DIR__ . '/.env');

if ($env === false) {
    die("Unable to load database configuration.");
}

$requiredSettings = ['DBHOST', 'USERNAME', 'PASSWORD', 'DATABASE'];
foreach ($requiredSettings as $setting) {
    if (!array_key_exists($setting, $env)) {
        die("Missing database configuration: " . $setting);
    }
}

$host = $env['DBHOST'];
$user = $env['USERNAME'];
$password = $env['PASSWORD'];
$dbname = $env['DATABASE'];

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>