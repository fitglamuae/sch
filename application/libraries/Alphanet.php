<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Alphanet {

    public $username = "";
    public $hash = "";
    public $url = "http://alphasms.biz/index.php?app=ws";

    function __construct() {

        $ci = & get_instance();
        
        $ci->db->select('S.*');
        $ci->db->from('sms_settings AS S');
        $ci->db->where('S.status', 1);
        $setting = $ci->db->get()->row();

        $this->username = $setting->alpha_username;
        $this->hash = $setting->alpha_hash;
    }

    function sendSMS($mobile, $message) {

        $username = $this->username;
        $hash = $this->hash;
    
        $params = array('u'=>$username, 'h'=>$hash, 'op'=>'pv', 'to'=>$mobile, 'msg'=>$message);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        curl_close($ch);
        echo $response;
    }
}

?>