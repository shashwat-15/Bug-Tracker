<?php
try {
    $dbh = new PDO("mysql:host=localhost;dbname=bug_tracker", "root", "");
} catch (Exception $e) {
    die("ERROR: Couldn't connect. {$e->getMessage()}");
}
