# 百度贴吧签到库

提供一个基于cookie验证的百度贴吧签到方案

## 安装

### 使用composer安装

在`composer.json`里的require增加

  `"friparia/tieba": "dev-master"`

然后执行 

  `composer update`

### 使用github

  直接使用

  `git clone git@github.com:friparia/tieba.git`

  然后编辑并执行`demo.php`

 
## 签到
  BDUSS是你的百度登陆后的cookie中的BDUSS的值

```php
$BDUSS = "你的BDUSS";
$kw = "要签到的吧名";
$tieba = new CTieba($BDUSS);
//提供单个贴吧签到，参数$kw为贴吧名
$tieba->sign($kw);
//提供所有自己喜欢的贴吧签到
$tieba->multisign();
```

## 自动签到

将多人的BDUSS存到`storing.json`里面，并将cron.php写入你的crontab中，每天一次，即可实现自动签到
