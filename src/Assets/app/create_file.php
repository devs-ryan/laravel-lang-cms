<?php

session_start();
include './autoload.php';

$file_template = "<?php

return [

];";

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
    
    //already exists
    if (file_exists($path)) {
        flash_error('A file with that name already exists.');
        header("Location: /lang-cms/file_index.php?lang=$lang");
        die();
    }
    
    //check correct name format
    if (!preg_match('/^([a-zA-Z]+_?){0,20}[a-zA-Z]+\.php$/', $file)) {
        flash_error('The file name does not match the required format.');
        header("Location: /lang-cms/file_index.php?lang=$lang");
        die();
    }
    
    
    //create file
    $create_file = fopen($path, "w");
    fwrite($create_file, $file_template);
    fclose($create_file);
    
    flash_success('The file was created successfully.');
    header("Location: /lang-cms/file_index.php?lang=$lang");
    die();
}

header("Location: /lang-cms");
die();


?>