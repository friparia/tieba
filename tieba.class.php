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

    public function login($username, $passord, $vcode_md5, $vcode){
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
            echo 'failed';
            return false;
        }else{
            echo $kw.'success';
            return true;
        }
    }

    public function msign(){
        $url = "http://tieba.baidu.com/c/c/forum/msign";
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
