<?php
include "Snoopy.class.php";
include "config.php";
if(!$handle = mysql_connect($mysql_host, $mysql_user, $mysql_pass))
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
$kw = mysql_escape_string($_POST['kw']);

if(isset($_POST['BDUSS']) && $_POST['BDUSS'] != NULL)
{
	$BDUSS = mysql_escape_string($_POST['BDUSS']);
	if(mysql_query("INSERT INTO bduss(bduss) VALUES('$BDUSS')"))
	{
		header("Location:autosign.php?errmsg=已经将您的记录储存下来，可以每日签到了");
	}

}
else if(isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] != NULL && $_POST['password'] != NULL)
{
	$snoopy = new Snoopy;
	$submit_url = "http://wappass.baidu.com/passport/";
	$snoopy->agent = "Mozilla/5.0 (MeeGo; NokiaN9) AppleWebKit/534.13 (KHTML, like Gecko) NokiaBrowser/8.5.0 Mobile Safari/534.13";
	$submit_vars['username'] = $_POST['username'];
	$submit_vars['password'] = $_POST['password'];
	$snoopy->submit($submit_url, $submit_vars);
	$cookie = $snoopy->cookies;
	$BDUSS = mysql_escape_string($cookie['BDUSS']);
	if(mysql_query("INSERT INTO bduss(bduss) VALUES('$BDUSS')")){
		header("Location:autosign.php?errmsg=已经将您的记录储存下来，可以每日签到了");
	}
}
else
{
	header("Location:autosign.php?errmsg=请输入必要内容");
}
