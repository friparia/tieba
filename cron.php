<?php
require_once './Tieba.class.php';
date_default_timezone_set("Asia/Shanghai");
$data = (array)json_decode(file_get_contents('./storing.json'));

$log = file_get_contents('./log');
foreach($data as $k=>$v){
    $tieba = new CTieba($v);
    $time = date("Y-m-d H:i:s");
    $result[$time] = $tieba->multisign();
}
$log .= json_encode($result);
file_put_contents('./log', $log);
