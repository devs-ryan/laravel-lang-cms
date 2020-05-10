<?php

//login error
if (isset($_GET['err']) && $_GET['err'] == 'Login') {
    ?> 
    <script>Swal.fire(
        'Access Denied',
        'The password you entered could not be verified.',
        'error'
    )</script>             
    <?php
}

//display error message
if (isset($_SESSION['err'])) {
    ?> 
    <script>Swal.fire(
        'Error',
        "<?=$_SESSION['err']?>",
        'error'
    )</script>             
    <?php
    unset($_SESSION['err']);
}

//display success message
if (isset($_SESSION['succ'])) {
    ?> 
    <script>Swal.fire(
        'Success',
        "<?=$_SESSION['succ']?>",
        'success'
    )</script>             
    <?php
    unset($_SESSION['succ']);
}

?>