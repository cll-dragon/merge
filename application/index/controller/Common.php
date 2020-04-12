<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
/**
 * 
 */
// 后期所有的后台控制器全部继承Common控制器
class Common extends Controller{
    // 保存用户的完整信息
    public function __construct(){
        // 先运行父类的构造方法
        parent::__construct();
        // 查看cookie里面是否有用户信息
        $user_info=cookie('user_info');
        // 是否有cookie信息，没有就传递url跳转去登录
        if(!$user_info){
            $redirect_uri = request()->url(true);
            dump($redirect_uri);
            $redirect_uri = urlencode($redirect_uri);
            dump($redirect_uri);
            exit;
            // 传递到Com控制器里面去调用单点登录
            $this->redirect('index/com/index',["redirect_uri"=>$redirect_uri]);
            // $url = 'http://mh.pcl.ac.cn:8080/idp/oauth2/authorize?redirect_uri='.$redirect_uri.'&state=123456&client_id=KAOQIN&response_type=code';
            // header("Location:".$url);exit;
        }
    }
}