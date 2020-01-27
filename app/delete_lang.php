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
    
    //delete folder
    if (is_dir($path)) {
        array_map('unlink', glob("$path/*"));
        rmdir($path);
        
        flash_success('The language was deleted successfully.');
        header("Location: /lang-cms/file_index.php");
        die();
    }
    
    //return back
    flash_error('The language could not be deleted.');
    header("Location: " . $_SERVER['HTTP_REFERER']);
    die();
}

header("Location: /lang-cms");
die();


?>