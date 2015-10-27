<?php 

ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'].'/redirect_generator/backend/model/redirect_generator.class.php';

$redirect_generator = new redirect_generator();
echo $res = $redirect_generator->init();

?>