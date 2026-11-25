<?php

session_start();

$allowed_languages = [
    "rw",
    "en",
    "fr"
];

$language = $_GET["lang"] ?? $_SESSION["language"] ?? "rw";

if (!in_array($language, $allowed_languages)) {
    $language = "rw";
}

$_SESSION["language"] = $language;

$translations = require __DIR__ . "/" . $language . ".php";