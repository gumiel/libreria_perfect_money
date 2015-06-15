<?php 
require('libPerfectMoney.php');
$config = array(
	'passAlternativo' => '89IUHIUKJKJ88kjH' 
	,'cuentaIPN' => 'U1234567'
	);


$pm = new LibPerfectMoney($config);

$pm->paso1();
?>
