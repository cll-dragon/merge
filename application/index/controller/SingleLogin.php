<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
header('Content-type: application/json; charset=utf-8');
//定义编码
// header( 'Content-Type:text/html;charset=utf-8 ');
class SingleLogin extends Controller{
    // 同步所有数据
    public function sync(){
        // 实例操作数据库的query对象
        $query = Db::name('t_user');
        // dump($query);exit;
        $jsonData = [
            'systemCode'=>'LNOA',
            'integrationKey'=>'password',
            'force'=>true,
            'timestamp'=> 1457063695725
        ];
        // 1457063695725
        // 调用login接口获取 tokenId
        $loginData = $this->getData('login',$jsonData);
        // dump($loginData['tokenId']);
        // dump($loginData);
        if($loginData['success']){
            $tokenId = $loginData['tokenId'];
            // $tokenId = "123dsdsbn";
            // 调用同步任务方法获取同步接口数据
            $syncTaskData = $this->syncTaskJson($tokenId);
            dump('1');
            dump($syncTaskData);
            // 如果有数据，调用同步完成接口，否则调用注销接口
            // $flag = true;
            $i=1;$j=20;
            while($j--){
                if($syncTaskData){
                    //如果同步成功，调用同步完成成功接口
                    if($syncTaskData['success']){
                        // 如果是用户信息，将用户数据保存到数据库中
                        if($syncTaskData['objectType'] == "TARGET_ACCOUNT"){
                            //将用户数据保存数据库
                            $insertData = [
                                "username" => $syncTaskData['data']['username'],
                                "password" => $syncTaskData['data']['password'],
                                "fullname" => $syncTaskData['data']['fullname']
                            ];
                            // dump($insertData);exit;
                            $query -> insert($insertData);
                            $syncTaskData['guid'] = "OA"."$i";
                            $i++;
                        }
                        $syncFinishSuccessData = $this->syncFinishSuccessJson($tokenId,$syncTaskData);
                        dump('2');
                        dump($syncFinishSuccessData);
                        if($syncFinishSuccessData['success']){
                            // 如果同步的数据成功调用同步完成成功接口
                            $syncTaskData = $this->syncTaskJson($tokenId);
                            dump('3');
                            dump($syncTaskData);
                            // if($syncTaskData){
                            //     $syncFinishData = $this->syncFinishSuccessJson($tokenId,$syncTaskData);
                            //     dump($syncFinishData);exit;
                            // }
                        }
                    }else{
                        //如果同步失败，调用同步完成失败接口
                        echo "<script>confirm('同步数据失败')</script>";
                    }
                    
                }else{
                    //调用注销接口
                    $logoutArray = [
                        "tokenId" => $tokenId,
                        'timestamp'=> 1457063695725
                    ];
                    $logoutData = $this->getData('logout',$logoutArray);
                    dump('注销tokenId');
                    dump($logoutData);
                    break;
                }
            }
        }else{
            echo "<script>confirm('调用登录接口失败')</script>";
        }
    }
    // 同步任务方法调用同步任务接口
    private function syncTaskJson($tokenId){
        $syncTask = [
            "tokenId" => $tokenId,
            "timestamp" => 1457063695725
        ];
        return $this->getData('syncTask',$syncTask);
    }
    // 同步完成成功方法调用同步完成接口
    private function syncFinishSuccessJson($tokenId,$syncTaskData){
        $syncFinishSuccess = [
            "tokenId" => $tokenId,
            "objectType" => $syncTaskData['objectType'],
            "objectCode" => $syncTaskData['objectCode'],
            "id" => $syncTaskData['id'],
            "success" => true,
            "guid" => $syncTaskData['guid'],
            "timestamp" => 1457063695725
        ];
        // "message"=> "数据写入错误",
        return $this->getData('syncFinish',$syncFinishSuccess);
    }
    // 同步完成失败方法调用同步完成接口
    private function syncFinishFailJson($tokenId,$syncTaskData){
        $syncFinishFail = [
            "tokenId" => $tokenId,
            "objectType" => $syncTaskData['objectType'],
            "objectCode" => $syncTaskData['objectCode'],
            "id" => $syncTaskData['id'],
            "success" => false,
            "guid" => $syncTaskData['guid'],
            "message"=> urlencode("数据写入错误"),
            "timestamp" => 1457063695725
        ];
        // "message"=> "数据写入错误",
        return $this->getData('syncFinish',$syncFinishFail);
    }

    // 下拉增量数据
    public function pull(){
        // 实例操作数据库的query对象
        $query = Db::name('t_user');
        // dump($query);exit;
        $jsonData = [
            'systemCode'=>'LNOA',
            'integrationKey'=>'password',
            'force'=>true,
            'timestamp'=> 1457063695725
        ];
        // 1457063695725
        // 调用login接口获取 tokenId
        $loginData = $this->getData('login',$jsonData);
        // dump($loginData['tokenId']);
        // dump($loginData);exit;
        if($loginData['success']){
            $tokenId = $loginData['tokenId'];
            // $tokenId = "123dsdsbn";
            // 调用同步任务方法获取同步接口数据
            $pullTaskData = $this->pullTaskJson($tokenId);
            dump('下拉任务数据');
            dump($pullTaskData);exit;
            // 如果有数据，调用同步完成接口，否则调用注销接口
            // $flag = true;
            $i=1;$j=20;
            while($j--){
                if($pullTaskData){
                    //如果同步成功，调用同步完成成功接口
                    if($pullTaskData['success']){
                        // 如果是用户信息，将用户数据保存到数据库中
                        // if($pullTaskData['objectType'] == "TARGET_ACCOUNT"){
                        //     //将用户数据保存数据库
                        //     $insertData = [
                        //         "username" => $syncTaskData['data']['username'],
                        //         "password" => $syncTaskData['data']['password'],
                        //         "fullname" => $syncTaskData['data']['fullname']
                        //     ];
                        //     // dump($insertData);exit;
                        //     $query -> insert($insertData);
                        //     $syncTaskData['guid'] = "OA"."$i";
                        //     $i++;
                        // }
                        $pullFinishSuccessData = $this->pullFinishSuccessJson($tokenId,$pullTaskData);
                        dump('下拉完成成功数据');
                        dump($pullFinishSuccessData);
                        if($pullFinishSuccessData['success']){
                            // 如果同步的数据成功调用同步完成成功接口
                            $pullTaskData = $this->pullTaskJson($tokenId);
                            dump('3');
                            dump($pullTaskData);
                            // if($syncTaskData){
                            //     $syncFinishData = $this->syncFinishSuccessJson($tokenId,$syncTaskData);
                            //     dump($syncFinishData);exit;
                            // }
                        }
                    }else{
                        //如果同步失败，调用同步完成失败接口
                        echo "<script>confirm('同步数据失败')</script>";
                    }
                    
                }else{
                    //调用注销接口
                    $logoutArray = [
                        "tokenId" => $tokenId,
                        'timestamp'=> 1457063695725
                    ];
                    $logoutData = $this->getData('logout',$logoutArray);
                    dump('注销tokenId');
                    dump($logoutData);
                    break;
                }
            }
        }else{
            echo "<script>confirm('调用登录接口失败')</script>";
        }
    }
    // 同步任务方法调用同步任务接口
    private function pullTaskJson($tokenId){
        $pullTask = [
            "tokenId" => $tokenId,
            "timestamp" => 1457063695725
        ];
        return $this->getData('pullTask',$pullTask);
    }
    // 同步完成成功方法调用同步完成接口
    private function pullFinishSuccessJson($tokenId,$pullTaskData){
        $pullFinishSuccess = [
            "tokenId" => $tokenId,
            "taskId" => $pullTaskData['taskId'],
            "success" => true,
            "guid" => $pullTaskData['guid'],
            "timestamp" => 1457063695725
        ];
        // "message"=> "数据写入错误",
        return $this->getData('pullFinish',$pullFinishSuccess);
    }
    // 同步完成失败方法调用同步完成接口
    private function pullFinishFailJson($tokenId,$pullTaskData){
        $pullFinishFail = [
            "tokenId" => $tokenId,
            "taskId" => $pullTaskData['taskId'],
            "success" => false,
            "guid" => $pullTaskData['guid'],
            "message"=> urlencode("数据写入错误"),
            "timestamp" => 1457063695725
        ];
        // "message"=> "数据写入错误",
        return $this->getData('pullFinish',$pullFinishFail);
    }

    // 调用接口的方法
    private function getData($method,$jsonData){
        // json数据的{}可以被转义
        $jsonData = json_encode($jsonData);
        // 调用java接口，有些字符需要被转义符合tomcat的url的RFC 3986规范
        $jsonData = str_replace('"', '%22', $jsonData);
        // 大括号改为%7d,有时候需要改
        // $jsonData = str_replace('"', '%7d', $jsonData);
        // $jsonData=urlencode($jsonData);
        $url='http://192.168.8.148:8080/bim-server/integration/api.json?method='.$method.'&request='.$jsonData;
        // $data = file_get_contents($url);
        // $data = json_decode($data,true);
        // dump($url);

        // 1、打开会话,curl发送get请求
        $ch = curl_init();
        $headers = array(
            "Content-type: application/json;charset='utf-8'",
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // 2、设置会话信息，需要设置请求方式、请求地址、请求参数
        curl_setopt($ch, CURLOPT_URL, $url); 
        //设置请求结果不直接输出
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        // 3、执行请求
        $data = curl_exec($ch);
        // dump($data);
        // // 4、关闭请求
        curl_close($ch);
        $data = json_decode($data,true);
        return $data;
    }
}

