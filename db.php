<?php
// db.php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "evoting";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) { die("DB connection failed: " . $conn->connect_error); }

if (session_status() === PHP_SESSION_NONE) {
  // Secure, consistent sessions
  ini_set('session.use_only_cookies', 1);
  ini_set('session.cookie_httponly', 1);
  session_start();
}

$EV_POSITIONS = [
  'President',
  'Vice President',
  'Director of Socials',
  'Assistant Secretary General',
  'Financial Secretary',
  'Director of Games',
  'Secretary General',
  'Director of Welfare'
];
