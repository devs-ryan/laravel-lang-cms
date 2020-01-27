<?php

session_start();
include './app/autoload.php';
include './app/helpers.php';

//verify user has access to this page - else redirect to login
if (!(isset($_SESSION['password']) && $_SESSION['password'] == env('ACCESS_PASSWORD'))) {
    header("Location: /lang-cms?err=Login");
    die();
}

//if lang not set correctly or file doesn't exist
if (!isset($_GET['lang']) || !in_array($_GET['lang'], getLangTypes()) || 
    !isset($_GET['file']) || !in_array($_GET['file'], getLangFiles($_GET['lang']))) {
    header("Location: /lang-cms");
    die();
}

global $project_path;
$lang = $_GET['lang'];
$file = $_GET['file'];
$path = $project_path . 'resources/lang/' . $lang . '/' . $file;

//get old key and value
if (isset($_SESSION['key'])){
    $old_key = $_SESSION['key'];
    unset($_SESSION['key']);
}
if (isset($_SESSION['value'])){
    $old_value = $_SESSION['value'];
    unset($_SESSION['value']);
}

//convert file to string
$text_file = file_get_contents($path) or die("Unable to open file!");

//mark all => separators
$markers = [];
for($i = 0; $i < strlen($text_file); $i++) {
    
    if ($text_file[$i] == '=' && $text_file[$i+1] == '>') {
        array_push($markers, $i);
    }
}

$keys = [];
$values = [];
foreach($markers as $marker) {
    array_push($keys, getKey($text_file, $marker));
    array_push($values, getValue($text_file, $marker));
}
$data = array_combine($keys, $values);

?>


<!DOCTYPE html>
<html>

<head>
    <title>Edit File</title>

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
        <h3>Edit File - '<?=$file;?>'</h3>
        <p>Below is a list of your language files keys and value pairs, use this page to edit them.</p>
        <small>* Hint: <i>Hold click and pull down on corner of a text box to increase the text area size.</i></small><br>
        <a class="btn btn-primary btn-sm" role="button" href="./file_index.php?lang=<?=$lang;?>"><i class="fas fa-arrow-left"></i> Back to file index</a>
    </div>
    
    <div class="container">
        <?php if (!env('EDIT_ONLY')) { ?>
            <div class="text-center">
                <a href="#" data-toggle="modal" data-target="#newKeyModal"><i class="fa fa-file"></i> Create a new key</a>
            </div>
        <?php } ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Key</th>
                    <th scope="col">Value</th>
                    <?php if (!env('EDIT_ONLY')) { ?><th class="text-center" scope="col">Delete</th><?php } ?>
                </tr>
            </thead>
            <tbody>
                    <?php
                 
                        foreach($data as $key => $value) { ?>
                            <tr>
                                <td><?=$key;?></td>
                                <td>
                                    <form method="post" action="./app/update_value.php">
                                        <input type="hidden" name="filename" value="<?=$file;?>">
                                        <input type="hidden" name="lang" value="<?=$lang;?>">
                                        <input type="hidden" name="key" value="<?=$key;?>">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <textarea name="value" rows="1" class="form-control"><?=stripslashes(htmlspecialchars($value));?></textarea>
                                            </div>
                                            <div class="col-md-3 pt-2 pt-md-0">
                                                <button class="btn btn-info" type="submit">Update Value</button>
                                            </div>
                                        </div>
                                    </form>
                                    
                                </td>
                                <?php if (!env('EDIT_ONLY')) { ?>
                                    <td class="text-center">
                                        <form method="post" action="./app/delete_key.php" onsubmit="return confirm('Are you sure you want to delete this key / value?');">
                                            <input type="hidden" name="filename" value="<?=$file;?>">
                                            <input type="hidden" name="lang" value="<?=$lang;?>">
                                            <input type="hidden" name="key" value="<?=$key;?>">
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
            if (empty($data) && !env('EDIT_ONLY')) {
                echo 'No keys exist in this file. <a href="#" data-toggle="modal" data-target="#newKeyModal">Click here</a> to create one.';
            }
        ?>
    </div>
    
    <?php if (!env('EDIT_ONLY')) { ?>
        <!-- New Language Modal -->
        <div class="modal" id="newKeyModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Create New Key?</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form method="post" action="./app/create_key.php">
                            <input type="hidden" name="filename" value="<?=$file;?>">
                            <input type="hidden" name="lang" value="<?=$lang;?>">
                            <div class="form-group">
                                <label>New Key:</label>
                                <input name="key" placeholder="eg. 'address_line1'" value="<?= $old_key ?? '' ?>" type="text" class="form-control">
                                <small>Alphanumeric characters and underscores only, no spaces.</small>
                            </div>
                            <div class="form-group">
                                <label>Value associated:</label>
                                <textarea placeholder="eg. '9 Golf Links Road'" name="value" rows="1" class="form-control"><?= $old_value ?? '' ?></textarea>
                                <small>* Hint: <i>Hold click and pull down on corner of text box to increase the text area size.</i></small>
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
    
</body>

</html>