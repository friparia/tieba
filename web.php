<?php
$base = dirname(__FILE__); 
require_once $base.'/Tieba.class.php';
if(!isset($_GET['action'])){
    $action = 'login';
}else{
    $action = $_GET['action'];
}


if($action == 'login'){
?>
<form method="post" action="?action=add">
用户名<input type="text" name="username"/>
密码<input type="password" name="password"/>
<input type="submit" name="submit" value="添加"/>
</form>
<?php
}elseif($action == 'add'){
    if(isset($_POST['vcode_md5'])){
        $vcode_md5 = $_POST['vcode_md5'];
    }else{
        $vcode_md5 = '';
    }
    if(isset($_POST['vcode'])){
        $vcode = $_POST['vcode'];
    }else{
        $vcode = '';
    }
    $result = CTieBa::login($_POST['username'], $_POST['password'], $vcode_md5, $vcode);
    if($result['status'] == true){
        $BDUSS = $result['BDUSS'];
        echo "已经添加";
        $data = (array)json_decode(file_get_contents('./storing.json'));
        $data[$_POST['username']] = $BDUSS;
        file_put_contents('./storing.json', json_encode($data));
    }
    else{
?>
请输入验证码<br/>
<form method="post" action="?action=add">
用户名<input type="text" name="username"/>
密码<input type="password" name="password"/>
<img src="<?php echo $result['vcode']['vcode_pic_url'];?>"/>
验证码<input type="text" name="vcode"/>
<input type="hidden" value="<?php echo $result['vcode']['vcode_md5']?>"name="vcode_md5"/>
<input type="submit" name="submit" value="添加"/>
</form>
<?php
    }

}

?>
