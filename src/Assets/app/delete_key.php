<?php

session_start();
include './autoload.php';

//verify user has access to this page - else redirect to login
if (!(isset($_SESSION['password']) && $_SESSION['password'] == env('ACCESS_PASSWORD'))) {
    header("Location: /lang-cms?err=Login");
    die();
}

//check filename and lang is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['filename']) 
    && isset($_POST['lang']) && isset($_POST['key'])) {
    
    global $project_path;
    $lang = $_POST['lang'];
    $file = $_POST['filename'];
    $key = $_POST['key'];
    
    $path = $project_path . 'resources/lang/' . $lang . '/' . $file;
    
    //check file exists
    if (file_exists($path)) {
        
        //add new key into file
        $text_file = file_get_contents($path) or die("Unable to open file!");
        
        //check key exists
        if (!preg_match("/'$key' *=>/", $text_file)) {
            flash_error('The key to be removed could not be found in the file.');
            header("Location: " . $_SERVER['HTTP_REFERER']);
            die();
        }
        
        //find section of file to replace
        preg_match("/'$key' *=>/", $text_file, $match, PREG_OFFSET_CAPTURE);
        $loc = $match[0][1] + strlen($key) + 2;

        $quote_count = 0;
        $quote_type = '';

        $start_marker = $loc - strlen($key)-6; $end_marker = 0;

        for($i = $loc; $i < strlen($text_file)-1; $i++) {
            //increase quote count when found
            if ($text_file[$i] == "'" || $text_file[$i] == '"') {
                if ($text_file[$i - 1] == "\\") {
                    continue;
                }

                if ($quote_count == 0) {
                    $quote_type = $text_file[$i];
                    $quote_count++;
                }
                else if ($text_file[$i] == $quote_type) {
                    $end_marker = $i;
                    break;
                }
            } 
        }

        $str_start = substr($text_file, 0, $start_marker);
        $str_end = substr($text_file, $end_marker + 3, strlen($text_file)-1);

        $new_text = $str_start . $str_end;
        
        //write new file
        file_put_contents($path, $new_text) or die("Unable to write to file!");
        
        
        flash_success('The key / value were removed successfully.');
        header("Location: " . $_SERVER['HTTP_REFERER']);
        die();
    }
    
    //return back
    flash_error('The file used to delete the key / value could not be located.');
    header("Location: " . $_SERVER['HTTP_REFERER']);
    die();
    
    
}

header("Location: /lang-cms");
die();

?>