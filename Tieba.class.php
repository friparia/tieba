<?php
require_once './Snoopy.class.php';
class CTieba{
    private $_BDUSS= '';
    private $_snoopy;

    public function __construct($BDUSS){
        $this->_BDUSS = $BDUSS;
        $this->_snoopy = new Snoopy;
        $this->_snoopy->cookies['BDUSS'] = $this->_BDUSS;
    }

    static public function login($username, $password, $vcode_md5 = null, $vcode = null){
        $login_url  = 'http://tieba.baidu.com/c/s/login';
        $snoopy = new Snoopy;
        $submit_vars['un'] = $username;
        $submit_vars['passwd'] = base64_encode($password);
        $submit_vars['vcode_md5'] = $vcode_md5;
        $submit_vars['vcode'] = $vcode;
        $snoopy->submit($login_url,$submit_vars);
        $response = (array)json_decode($snoopy->results);
        if($response['error_code'] != 0){
            $result['status'] = false;
            $result['vcode'] = get_object_vars($response['anti']);
        }else{
            $result['status'] = true;
            $user = get_object_vars($response['user']);
            $result['BDUSS'] = $user['BDUSS'];
        }
        return $result;
    }

    public function getTbs(){
        $tbs_url = "http://tieba.baidu.com/dc/common/tbs";
        $this->_snoopy->submit($tbs_url);
        $response = (array)json_decode($this->_snoopy->results);
        return $response['tbs'];
    }

    public function sign($kw){
        $sign_url = "http://tieba.baidu.com/c/c/forum/sign";
        $post_data=array('kw'=>$kw,'tbs'=>$this->getTbs());
        $this->_snoopy->fetch($sign_url."?".$this->encrypt($post_data));
        $response = (array)json_decode($this->_snoopy->results);
        if($response['error_code'] != 0){
            $result['status'] = false;
        }else{
            $result['status'] = true;
        }
        $result['msg'] = $response['error_msg'];
        $result['kw'] = $kw;
        return $result;
    }

    public function multisign(){
        $forum_list = $this->getFavForums();
        $result = array();

        foreach($forum_list as $forum){
            $forum = (array)$forum;
            $kw = $forum['name'];
            $result[] = $this->sign($kw);
        }
        return $result;
    }

    public function getFavForums(){
        $fav_url = "http://tieba.baidu.com/c/f/forum/like";
        $post_data = array('tbs'=>$this->getTbs());

        $this->_snoopy->fetch($fav_url."?".$this->encrypt($post_data));
        $response = (array)json_decode($this->_snoopy->results);
        return $response['forum_list'];
    }

    public function msign(){
        $msign_url = "http://tieba.baidu.com/c/c/forum/msign";
    }

    public function addPost($content,$kw,$tid){
        $addpost_url = "http://tieba.baidu.com/c/c/post/add";
    }

    public function addThread($content,$kw, $title){
        $addthread_url = "http://tieba.baidu.com/c/c/thread/add";
    }

    public function encrypt($s){
        ksort($s);
        $a = '';
        $b ='';
        foreach($s as $j=>$i){
            $a.=$j.'='.$i;
            $b.=$j.'='.urlencode($i).'&';
        };
        $a=strtoupper(md5($a.'tiebaclient!!!'));
        return $b.'sign='.$a;
    }
}

?>