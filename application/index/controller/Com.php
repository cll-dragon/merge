<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
/**
 * 
 */
// 后期所有的后台控制器全部继承Common控制器
class Com extends Controller{
    public function index(){
        $redirect_uri = $_GET['redirect_uri'];
        dump($redirect_uri);exit; 
    }
}