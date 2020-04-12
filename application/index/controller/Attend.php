<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;
/**
 * 进入实验室人数查询
 */
class Attend extends Controller{
    // 跳转统一登录页面获取code
    public function inde(){
        $redirect_uri = 'http://192.168.131.11:8083/index.php/attend';
        $redirect_uri = urlencode($redirect_uri);
        $url = 'http://mh.pcl.ac.cn:8080/idp/oauth2/authorize?redirect_uri='.$redirect_uri.'&state=123456&client_id=KAOQIN&response_type=code';
        header("Location:".$url);exit;
    }
    public function index2(Request $request){
        //回调地址会传回一个code，则我们根据code去获取openid和授权获取到的access_token
        $code = $_GET['code'];
        $state = $_GET['state'];
        echo "code：$code"."<br/>";
        echo "state：$state"."<br/>";
        exit;
    }
    public function index(Request $request){
        //回调地址会传回一个code，则我们根据code去获取openid和授权获取到的access_token
        $code = $_GET['code'];
        $state = $_GET['state'];
        echo "code：$code"."<br/>";
        echo "state：$state"."<br/>";
        // exit;
        $url = "http://mh.pcl.ac.cn:8080/idp/oauth2/getToken";
        $postData = 'client_id=KAOQIN&grant_type=authorization_code&code='.$code.'&client_secret=f846cdc21e5a4789883e74dc3eaba17e';
        // 调用getToken接口获取access_token信息，token有效时长，token刷新续期，用户登录id
        $res = $this->http_curl($url,$postData,'post');
        dump($res);
        $access_token = $res['access_token'];
        // access_token到期后刷新续期
        $refresh_token = $res['refresh_token'];
        // access_token有效时长
        $expires_in = $res['expires_in'];
        // 用户id
        $uid = $res['uid'];

        // 调用getUserInfo接口获取用户账号信息
        $urltoc = 'http://mh.pcl.ac.cn:8080/idp/oauth2/getUserInfo?access_token='.$access_token.'&client_id=KAOQIN';
        $resinfo = $this->http_curl($urltoc);
        dump($resinfo);exit;


        // 判断是否是get请求
        if($request->isGet()){  
            // $query = db("attendrecord");
            // dump($query);exit;
            // $date1 = date("Y-m-d");
            // $date2 = date("Y-m-d",strtotime("-1 day"));
            // $date3 = date("Y-m-d",strtotime("-2 days"));
            // // dump($date1);
            // // dump($date2);exit;
            // $data1 = Db::query("select count(distinct ar_cardno) as num from attendrecord where str_to_date(ar_datetime, '%Y-%m-%d')='$date1';");
            // $data2 = Db::query("select count(distinct ar_cardno) as num from attendrecord where str_to_date(ar_datetime, '%Y-%m-%d')='$date2';");
            // $data3 = Db::query("select count(distinct ar_cardno) as num from attendrecord where str_to_date(ar_datetime, '%Y-%m-%d')='$date3';");
            // // dump($data);exit;
            // $this->assign('date1',$date1);
            // $this->assign('date2',$date2);
            // $this->assign('date3',$date3);
            // $this->assign('data1',$data1);
            // $this->assign('data2',$data2);
            // $this->assign('data3',$data3);
            return $this->fetch('index2');
        }
        $date = input('date');
        $data = Db::query("select count(distinct ar_cardno) as num from attendrecord where str_to_date(ar_datetime, '%Y-%m-%d')='$date';");
        // dump($data);exit;
        $this->assign('date',$date);
        $this->assign('data',$data);
        return $this->fetch('index1');
    }
    // 调用接口
    public function http_curl($url,$postData='',$type='get',$res='json'){
        dump($url);
        dump($type);
        dump($res);
        dump($postData);
        //1.初始化curl
        $ch = curl_init();
        //2.设置curl的参数
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不验证证书
        //设置头文件的信息作为数据流输出
        // curl_setopt($ch, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //加入重定向处理
        // curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
        if ($type == 'post') {
            echo "post方式"."<br/>";
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }
        //3.采集
        $data = curl_exec($ch);
        //4.关闭
        curl_close($ch);
        if ($res == 'json') {
            return json_decode($data,true);
        }else{
            return $data;
        }
    }


    private function getUserInfo($access_token,$client_id){
        $url = 'https://192.168.8.148:8080/idp/oauth2/getUserInfo?access_token='.$access_token.'&client_id='.$client_id;
    }

    
}