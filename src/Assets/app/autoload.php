<?php

if(session_id() == '' || !isset($_SESSION)) {
    session_start();
}

$path = __DIR__;
$project_path = __DIR__ . '/../../../';

//read and store env variables
if(file_exists($path . '/../env.php')) {
    
    include $path . '/../env.php';

    foreach ($variables as $key => $value) {
        
        //if !ENABLED die()
        if ($key == 'ENABLED' && $value == false) {
            echo "<h1>ERROR 404!</h1>";
            echo "<h3>The language CMS has been disabled by your system admin.</h3>";
            die();
        }
        
        putenv("$key=$value");
    }
}

/****************************
 * env($key, $default = null)
 ****************************
 * Helper to get env variables
 *
 * @param    string  $key The env variable to look up
 * @param    mixed   $default A default return value when key not found
 * @return      string or null
 *
 */
if(!function_exists('env')) {
    function env($key, $default = null) {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        return $value;
    }
}

/****************************
 * getLangTypes()
 ****************************
 * Returns all folders found inside Lang directories
 *
 * @return      array of strings
 *
 */
if(!function_exists('getLangTypes')) {
    function getLangTypes() {
        global $project_path;
        
        $dirs_wpath = array_filter(glob($project_path . 'resources/lang/*'), 'is_dir');
        $dirs = [];
        foreach ($dirs_wpath as $curr) {
            array_push($dirs, str_replace($project_path . 'resources/lang/', '', $curr));
        }
        return $dirs;
    }
}


/****************************
 * getLangFiles()
 ****************************
 * Returns all folders found inside Lang directories
 *
 * @param    string  $lang The active lang short code eg. 'en'
 * @return      array of strings
 *
 */
if(!function_exists('getLangFiles')) {
    function getLangFiles($lang) {
        global $project_path;
        
        $files_wpath = array_filter(glob($project_path . 'resources/lang/'.$lang.'/*.php'));
        $files = [];
        
        $ignore = ['auth.php', 'pagination.php', 'passwords.php', 'validation.php'];
        
        foreach ($files_wpath as $curr) {
            $filename = str_replace($project_path . 'resources/lang/'.$lang.'/', '', $curr);
            if (!in_array($filename, $ignore)) array_push($files, $filename);
        }
        return $files;
    }
}


/****************************
 * flash_error($msg)
 ****************************
 * Flashes an error message to session variable 'err'
 *
 * @param    string  $msg The error message
 * @param    array of strings  $values Additional values to be flashed
 * @return      string
 *
 */
if(!function_exists('flash_error')) {
    function flash_error($msg, $values = null) {
        $_SESSION['err'] = $msg;
        
        foreach ($values as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }
}

/****************************
 * flash_error($msg)
 ****************************
 * Flashes a success message to session variable 'succ'
 *
 * @param    string  $msg The error message
 * @return      string
 *
 */
if(!function_exists('flash_success')) {
    function flash_success($msg) {
        $_SESSION['succ'] = $msg;
    }
}





























?>