<?php
$base = dirname(__FILE__); 
require_once $base.'/Tieba.class.php';
date_default_timezone_set("Asia/Shanghai");
$data = (array)json_decode(file_get_contents($base.'/storing.json'));

$log = file_get_contents($base.'/log');
$result = array();
foreach($data as $k=>$v){
    $tieba = new CTieba($v);
    $time = date("Y-m-d H:i:s");
    $result[$time] = $tieba->multisign();
}
$log .= json_encode($result);
file_put_contents($base.'/log', $log);
