<?php
require 'vendor/autoload.php';
class CTieba{
    private $_headers = array();
    const TBS_URL = "http://tieba.baidu.com/dc/common/tbs";
    const SIGN_URL = "http://tieba.baidu.com/c/c/forum/sign";
    const POST_URL = "http://tieba.baidu.com/c/c/post/add";
    const MSIGN_URL = "http://tieba.baidu.com/c/c/forum/msign";
    const ADDTHREAD_URL = "http://tieba.baidu.com/c/c/thread/add";

    public function __construct($BDUSS){
        $this->_headers = array(
            'Cookie' => 'BDUSS='.$BDUSS,
            'Host'  => 'tieba.baidu.com',
            'Referer' => 'http://tieba.baidu.com/',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.111 Safari/537.36',
        );
    }

    public function getTbs(){
        $response = Requests::get(self::TBS_URL, $this->_headers);
        $response = json_decode($response->body);
        return $response->tbs;
    }

    public function sign($kw){
        $data = array('kw' => $kw,'tbs' => $this->getTbs());
        $response = Requests::get(self::SIGN_URL."?".$this->encrypt($data), $this->_headers);
        $response = json_decode($response->body);
        $result = new StdClass;
        if($response->error_code != 0){
            $result->status = false;
        }else{
            $result->status = true;
        }
        $result->msg = $response->error_msg;
        $result->kw = $kw;
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
