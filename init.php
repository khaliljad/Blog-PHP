<?php
require 'admin-control-pannel/connection.php';

$sessionId = '';
if (isset($_SESSION['userid'])) {
    $sessionId = $_SESSION['userid'];
}

$userSession = '';
if (isset($_SESSION['user'])) {
    $userSession = $_SESSION['user'];
}

$tpl = 'includs/templates/';
$lang = 'includs/languages/';
$func = 'includs/functions/';
$css = 'theme/css/';
$js = 'theme/js/';
$vids = 'theme/vid/';

// ---------------------------------includs the important fils------------------------------//


// include $lang . 'arabe.php';
include $lang . 'english.php';
include $func . 'function.php';
include $tpl . 'header.php';
if(!isset($noNav)) { include $tpl . 'nav.php'; };
