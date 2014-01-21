<?php
require_once './tieba.class.php';
require_once './config.php';
$tieba = new CTieba($BDUSS);
$tieba->sign($kw);
