<?php
require 'connection.php';

$tpl = 'includs/templates/';
$lang = 'includs/languages/';
$func = 'includs/functions/';
$css = 'theme/css/';
$js = 'theme/js/';

// ---------------------------------includs the important fils------------------------------//


// include $lang . 'arabe.php';
include $lang . 'english.php';
include $func . 'function.php';
include $tpl . 'header.php';
if(!isset($noNav)) { include $tpl . 'nav.php'; };
