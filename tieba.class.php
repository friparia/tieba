<?php
require_once './Snoopy.class.php';
class CTieba{
    private $BDUSS= '';

    public function __construct($BDUSS){
        $this->BDUSS=$BDUSS;
    }

    public function login(){
        $un;
        $passwd;
        $vcode_md5;
        $vcode;
    }

    public function getTbs(){
        $url = "http://tieba.baidu.com/dc/common/tbs";
        $snoopy = new Snoopy;
        $snoopy->cookies['BDUSS'] = $this->BDUSS;
        $snoopy->submit($url);
        $response = (array)json_decode($snoopy->results);
        return $response['tbs'];

    }

    public function sign($kw){
        $url = "http://tieba.baidu.com/c/c/forum/sign";
        $dat=array('BDUSS'=>$this->BDUSS,'kw'=>$kw,'tbs'=>$this->getTbs());
        $snoopy = new Snoopy;
        $snoopy->fetch($url."?".$this->encrypt($dat));
        var_dump(json_decode($snoopy->results));

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

