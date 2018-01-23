<?php
require_once 'config.php';
$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
try{
    $connectStr = DB_DRIVER.':host='.DB_HOST.';dbname='.DB_NAME;
    $db = new PDO($connectStr,DB_USER,DB_PASS);
    $db->exec("set names utf8");
}catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
echo '<pre>';
var_dump($_POST);
$createNewTable = $db->prepare("CREATE TABLE `students` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`name` varchar(50) NOT NULL,
`estimation` float NOT NULL,
`budget` tinyint(4) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");