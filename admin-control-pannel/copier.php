<?php 

session_start();
// ******* title for header ******* \\
$title = '';

// ******* check if user login ****** \\
if (isset($_SESSION['userName'])) {

    include 'init.php';

    // ******** check and get the name of page ******** \\
    $singlPage = isset($_GET['page']) ? $page = $_GET['page'] : $page = 'manage';

        if ($page == 'manage') {

        } elseif ($page == 'edit') {

        } elseif ($page == 'update') {

        } else {

        };

    include $tpl . "footer.php ";

} else {

    header('Location: index.php');
    exit;

}