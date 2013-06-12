<?php
include "Snoopy.class.php";

if(!$handle = mysql_connect("数据库地址", "用户名", "密码"))
{
	switch(mysql_errno())
	{
	default:
		die("数据库连接失败");
	}
}
mysql_query("SET NAMES UTF8", $handle);
mysql_query("SET collation_connection = 'utf8_general_ci' ", $handle);
mysql_query("SET sql_mode = '' ", $handle);
mysql_select_db("bduss", $handle) or die('发生错误，错误号:' + mysql_error());



$res = mysql_query("SELECT * FROM bduss");
while($row = mysql_fetch_assoc($res))
{
	$BDUSS = $row['bduss'];
	$tblist = getTbList($BDUSS); 
	doAllSignup($BDUSS,$tblist);
}

function getTbList($BDUSS)
{
	$res = mysql_query("SELECT kw FROM bduss WHERE bduss='$BDUSS'");
	while($row = mysql_fetch_assoc($res))
	{
		$tbl = $row['kw'];
	}
	return explode(",",$tbl);

}

function doAllSignup($BDUSS,$tblist)
{
	foreach($tblist as $tb)
	{
		$dat=array('BDUSS'=>$BDUSS,'kw'=>$tb,'tbs'=>getTbs($BDUSS));
		doSignup($dat);
	}

}

function getTbs($BDUSS)
{
	$snoopy = new Snoopy;
	$submit_url = "http://tieba.baidu.com/dc/common/tbs";
	$submit_vars['none'] ="none";
	$snoopy->cookies["BDUSS"] = $BDUSS;
	$snoopy->agent = "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)";
	$snoopy->rawheaders["Pragma"] = "no-cache";
	$snoopy->submit($submit_url, $submit_vars);
	$res=json_decode($snoopy->results);
	$tbs=$res->{"tbs"};
	return $tbs;

}

function getPostData($s){
	$a = '';
	$b ='';
	foreach($s as $j=>$i){
		$a.=$j.'='.$i;
		$b.=$j.'='.urlencode($i).'&';
	};
	$a=strtoupper(md5($a.'tiebaclient!!!'));
	return $b.'sign='.$a;
};

function doSignup($dat){
	$ci=curl_init('http://c.tieba.baidu.com/c/c/forum/sign');
	curl_setopt($ci,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ci,CURLOPT_POST,1);
	curl_setopt($ci,CURLOPT_POSTFIELDS,getPostData($dat));
	$op=curl_exec($ci);
	$o=json_decode($op,true);
}
