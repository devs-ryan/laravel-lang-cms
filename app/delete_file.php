<?php

session_start();
include './autoload.php';

//verify user has access to this page - else redirect to login
if (!(isset($_SESSION['password']) && $_SESSION['password'] == env('ACCESS_PASSWORD'))) {
    header("Location: /lang-cms?err=Login");
    die();
}

//check filename and lang is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['filename']) && isset($_POST['lang'])) {
    global $project_path;
    $lang = $_POST['lang'];
    $file = $_POST['filename'];
    
    $path = $project_path . 'resources/lang/' . $lang . '/' . $file;
    
    //delete file
    if (file_exists($path)) {
        unlink($path);
        flash_success('The file was deleted successfully.');
        header("Location: " . $_SERVER['HTTP_REFERER']);
        die();
    }
    
    //return back
    flash_error('The file could not be deleted.');
    header("Location: " . $_SERVER['HTTP_REFERER']);
    die();
}

header("Location: /lang-cms");
die();

?>