<?php

session_start();
include './app/autoload.php';

//check login credentials and create session
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] == env('ACCESS_PASSWORD')) {
        $_SESSION['password'] = $_POST['password'];
    }
    else {
        if (isset($_SESSION['password'])) unset($_SESSION['password']);
    }
}

//verify user has access to this page - else redirect to login
if (!(isset($_SESSION['password']) && $_SESSION['password'] == env('ACCESS_PASSWORD'))) {
    header("Location: /lang-cms?err=Login");
    die();
}

//if lang not set correctly redirect to first language
if (!isset($_GET['lang']) || !in_array($_GET['lang'], getLangTypes())) {
    $langs = getLangTypes();
    
    //pass success or error message from lang delete
    if (strpos($_SERVER['REQUEST_URI'], 'succ=Lang') !== false) {
        $append = "&succ=Lang";
    }
    if (strpos($_SERVER['REQUEST_URI'], 'err=Lang') !== false) {
        $append = "&err=Lang";
    }

    //if no languages exit create 'en'
    if (empty($langs)) {
        mkdir('../../resources/lang/en', 0777, true);
        header("Location: /lang-cms/file_index.php?lang=en" . $append);
        die();
    }
    else {
        $lang = $langs[0];
        header("Location: /lang-cms/file_index.php?lang=" . $lang . $append);
        die();
    }
}

$curr_lang = $_GET['lang'];
$langs = getLangTypes();
$files = getLangFiles($curr_lang);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Lang CMS - Files</title>

    <!-- Bootstrap -->
    <script src="./includes/jquery.min.js"></script>
    <link href="./includes/bootstrap-4.3.1-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="./includes/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>

    <!--Fontawesome-->
    <link rel="stylesheet" href="./includes/fontawesome-free-5.12.0-web/css/all.min.css">
    
    <!--Sweet Alert-->
    <script src="./includes/sweetalert2.all.min.js"></script>

    <!--Custom styles-->
    <link rel="stylesheet" type="text/css" href="./css/app.css">
</head>

<body>
   
   <!-- HEADER -->
    <div class="container pt-5 pb-3">
        <h3>Lang Files - Index</h3>
        <p>Below is a list of the current language files in your project.</p>
        <div class="row">
            <!-- Select a language -->
            <div class="col-md-6">
                <div class="form-group lang-select-area">
                    <label>
                        Select a language: 
                    </label>
                    <select id="lang-select" class="form-control form-control-sm">
                    <?php
                        foreach($langs as $lang) { 
                            if ($lang == $curr_lang) { ?>
                                <option value="<?=$lang;?>" selected><?=$lang;?></option> 
                            <?php }
                            else { ?>
                                <option value="<?=$lang;?>"><?=$lang;?></option> 
                            <?php }
                        }
                    ?>
                    </select>
                    <?php if (!env('EDIT_ONLY')) { ?>
                        <form method="post" action="./app/delete_lang.php" onsubmit="return confirm('Are you sure you want to delete this language?');">
                            <input type="hidden" name="lang" value="<?=$curr_lang;?>">
                            <button type="submit" class="btn btn-link text-danger btn-sm">
                                Delete this language
                            </button>
                        </form>
                    <?php } ?>
                </div>
            </div>
            <!-- Create New -->
            <?php if (!env('EDIT_ONLY')) { ?>
                <div class="col-md-6 text-right my-auto">
                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#newLangModal">
                        <i class="fa fa-plus"></i> Create New Language
                    </button>
                </div>
            <?php } ?>
        </div>
    </div>
    
    <div class="container">
        <?php if (!env('EDIT_ONLY')) { ?>
            <div class="text-center">
                <a href="#" data-toggle="modal" data-target="#newFileModal"><i class="fa fa-file"></i> Create a new file</a>
            </div>
        <?php } ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Filename</th>
                    <th class="text-center" scope="col">Edit</th>
                    <?php if (!env('EDIT_ONLY')) { ?><th class="text-center" scope="col">Delete</th><?php } ?>
                </tr>
            </thead>
            <tbody>
                    <?php
                 
                        foreach($files as $file) { ?>
                            <tr>
                                <td><?=$file;?></td>
                                <td class="text-center">
                                    <a href="./file_edit.php?lang=<?=$curr_lang;?>&file=<?=$file;?>" class="btn btn-info" role="button">
                                        <i class="text-white fas fa-edit"></i>
                                    </a>
                                </td>
                                <?php if (!env('EDIT_ONLY')) { ?>
                                    <td class="text-center">
                                        <form method="post" action="./app/delete_file.php" onsubmit="return confirm('Are you sure you want to delete this file?');">
                                            <input type="hidden" name="filename" value="<?=$file;?>">
                                            <input type="hidden" name="lang" value="<?=$curr_lang;?>">
                                            <button class="btn btn-danger">
                                                <i class="text-white fas fa-trash"></i>
                                            </button>
                                        </form>

                                    </td>
                                <?php } ?>
                            </tr>
                        <?php }
                    ?> 
            </tbody>
        </table>
        <?php
            if (empty($files) && !env('EDIT_ONLY')) {
                echo 'No files exist in this language. <a href="#" data-toggle="modal" data-target="#newFileModal">Click here</a> to create one.';
            }
        ?>
    </div>
    
    <?php if (!env('EDIT_ONLY')) { ?>
        <!-- New Language Modal -->
        <div class="modal" id="newLangModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Create New Language?</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form method="post" action="./app/create_lang.php">
                            <div class="form-group">
                                <label>New language shortcode:</label>
                                <input name="lang" placeholder="eg. 'es'" type="text" class="form-control">
                            </div>
                            <div class="text-right">
                                <button class="btn btn-success" type="submit">
                                    <i class="fa fa-check"></i> Create
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- New File Modal -->
        <div class="modal" id="newFileModal">
            <div class="modal-dialog">
                <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Create New File?</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <form method="post" action="./app/create_file.php">
                            <div class="form-group">
                                <label>New File Name:</label>
                                <input type="hidden" name="lang" value="<?=$curr_lang;?>">
                                <input name="filename" placeholder="eg. 'about_us.php'" type="text" class="form-control">
                                <small>Alphabetical characters only, no spaces (use underscore instead), and must end with '.php'</small>
                            </div>
                            <div class="text-right">
                                <button class="btn btn-success" type="submit">
                                    <i class="fa fa-check"></i> Create
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    <?php } ?>
    
    <!-- Messages --> 
    <?php include './app/msg.php'; ?>
    
    <!-- SCRIPTS --> 
    <script>
        $('#lang-select').on('change', function() {
            window.location.href = "/lang-cms/file_index.php?lang=" + this.value;
        });
    </script>
    
</body>

</html>