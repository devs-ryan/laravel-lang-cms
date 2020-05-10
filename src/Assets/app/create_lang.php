<?php

session_start();
include './autoload.php';

//verify user has access to this page - else redirect to login
if (!(isset($_SESSION['password']) && $_SESSION['password'] == env('ACCESS_PASSWORD'))) {
    header("Location: /lang-cms?err=Login");
    die();
}

//check filename and lang is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['lang'])) {
    global $project_path;
    $lang = $_POST['lang'];
    
    $path = $project_path . 'resources/lang/' . $lang;
    
    //already exists
    if (is_dir($path)) {
        flash_error('The language could not be created because it already exists.');
        header("Location: /lang-cms/file_index.php?lang=$lang");
        die();
    }
    
    //check correct name format
    if (!preg_match('/^[a-zA-Z]*$/', $lang)) {
        flash_error('The language shortcode does not match the required format.');
        header("Location: /lang-cms/file_index.php?lang=$lang");
        die();
    }
    
    //create folder
    mkdir($path, 0777, true);
    flash_success('The language was created successfully.');
    header("Location: /lang-cms/file_index.php?lang=$lang");
    die();
}

header("Location: /lang-cms");
die();


?>