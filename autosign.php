<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8">
</head>
<body>
<h1><font color="red"><?php if(isset($_GET['errmsg'])) echo $_GET['errmsg']; ?></font></h1>
<h1><font color="red">自动签到会获取您的很重要的信息(百度账号)，如果您不信任，可以自己下载源代码并实现，代码地址<a href="http://github.com/friparia">github.com/friparia</a></font></h1>
<h1>如果你需要时不时的上贴吧看看，请遵循以下步骤，如果你不再上贴吧，仅仅是签到，请看后面那个</h1>
<h2>通过浏览器找到在baidu.com下的BDUSS的cookie的值，并将其填入如下的表格</h2>
<h2>如果不会找，请遵循以下步骤</h2>
<h3>1.打开www.baidu.com</h3>
<h3>2.在地址栏输入JavaScript:with(document)write(cookie.match(/BDUSS=\S+/));(或者控制台也行)</h3>
<h3>3.将页面上的值复制下来填入(不含最后的分号)</h3>
<form method="post" action="takebduss.php">
	您的BDUSS: <input name="BDUSS" type="text"/><br />
	<input type="submit"/>
</form>

<h1>如果你不需要登录贴吧看看或访问，请使用这里的方法</h1>
<h2>不负责检验您的密码是否正确，请自行确保用户名密码正确</h2>
<h2>请输入用户名和密码</h2>
<form method="post" action="takebduss.php">
	用户名:<input type="text" name="username"/><br/>
	密码:<input type="password" name="password" /><br/>
	<input type="submit" />
</form>

</body>
</html>
