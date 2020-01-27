<?php

session_start();
include './autoload.php';

//verify user has access to this page - else redirect to login
if (!(isset($_SESSION['password']) && $_SESSION['password'] == env('ACCESS_PASSWORD'))) {
    header("Location: /lang-cms?err=Login");
    die();
}

//check filename and lang is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['filename']) && isset($_POST['lang'])
   && isset($_POST['key']) && isset($_POST['value'])) {
    
    global $project_path;
    $lang = $_POST['lang'];
    $file = $_POST['filename'];
    $key = $_POST['key'];
    $value = $_POST['value'];
    
    $path = $project_path . 'resources/lang/' . $lang . '/' . $file;
    
    //check file exists
    if (file_exists($path)) {
        
        //validate key
        if (!preg_match('/^[a-zA-Z0-9_]*$/', $key)) {
            flash_error('The key does not match the required format.', ['value' => $value, 'key' => $key]);
            header("Location: " . $_SERVER['HTTP_REFERER']);
            die();
        }
        
        //escape any single quotes in value
        $value = str_replace("'", "\'", $value);
        
        //add new key into file
        $text_file = file_get_contents($path) or die("Unable to open file!");
        
        //check duplicate key
        if (preg_match("/'$key' *=>/", $text_file)) {
            flash_error('The key already exists and cannot be duplicated.', ['value' => $value, 'key' => $key]);
            header("Location: " . $_SERVER['HTTP_REFERER']);
            die();
        }
        
        //count back until '];'
        $marker = 0;
        for($i = strlen($text_file)-1; $i >= 0; $i--) {
    
            if ($text_file[$i] == ']' && $text_file[$i+1] == ';') {
                $marker = $i;
                break;
            }
        }
        
        $insert = "    '" . $key . "' => '" . $value . "',\n";
        $new_text = substr_replace($text_file, $insert, $marker, 0);
        
        //write new file
        file_put_contents($path, $new_text) or die("Unable to write to file!");
        
        
        flash_success('The new key and value was added successfully.');
        header("Location: " . $_SERVER['HTTP_REFERER']);
        die();
    }
    
    //return back
    flash_error('The file to add the new key could not be located.');
    header("Location: " . $_SERVER['HTTP_REFERER']);
    die();
    
    
}

header("Location: /lang-cms");
die();

?>