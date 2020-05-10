<?php

require_once "db/db_config.php";
include "class/ecommerce.php";
$data = new Ecommerce($DB_con);

echo "<pre>"; 
$resTaskOne = $data->getTaskOne();
//print_r($resTaskOne);

$resTaskTwo = $data->getTaskTwo();
print_r($resTaskTwo);

?>