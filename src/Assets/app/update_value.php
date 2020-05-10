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
        
        //escape any single quotes in value
        $value = str_replace("'", "\'", $value);
        
        //add new key into file
        $text_file = file_get_contents($path) or die("Unable to open file!");
        
        //check key exists
        if (!preg_match("/'$key' *=>/", $text_file)) {
            flash_error('The key to be updated could not be found in the file.', ['value' => $value]);
            header("Location: " . $_SERVER['HTTP_REFERER']);
            die();
        }
        
        //find section of file to replace
        preg_match("/'$key' *=>/", $text_file, $match, PREG_OFFSET_CAPTURE);
        $loc = $match[0][1] + strlen($key) + 2;

        $quote_count = 0;
        $quote_type = '';

        $start_marker = 0; $end_marker = 0;

        for($i = $loc; $i < strlen($text_file)-1; $i++) {
            //increase quote count when found
            if ($text_file[$i] == "'" || $text_file[$i] == '"') {
                if ($text_file[$i - 1] == "\\") {
                    continue;
                }

                if ($quote_count == 0) {
                    $quote_type = $text_file[$i];
                    $quote_count++;
                    $start_marker = $i;
                }
                else if ($text_file[$i] == $quote_type) {
                    $end_marker = $i;
                    break;
                }
            } 
        }

        $str_start = substr($text_file, 0, $start_marker+1);
        $str_end = substr($text_file, $end_marker, strlen($text_file)-1);

        $new_text = $str_start . $value . $str_end;
        
        //write new file
        file_put_contents($path, $new_text) or die("Unable to write to file!");
        
        
        flash_success('The new key / value was added successfully.');
        header("Location: " . $_SERVER['HTTP_REFERER']);
        die();
    }
    
    //return back
    flash_error('The file used to add the new key / value could not be located.');
    header("Location: " . $_SERVER['HTTP_REFERER']);
    die();
    
    
}

header("Location: /lang-cms");
die();

?>