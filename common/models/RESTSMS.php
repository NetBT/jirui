<?php
namespace common\models;
use yii\base\Exception;

class RESTSMS
{
/*
 *  Copyright (c) 2014 The CCP project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a Beijing Speedtong Information Technology Co.,Ltd license
 *  that can be found in the LICENSE file in the root of the web site.
 *
 *   http://www.yuntongxun.com
 *
 *  An additional intellectual property rights grant can be found
 *  in the file PATENTS.  All contributing project authors may
 *  be found in the AUTHORS file in the root of the source tree.
 */
    //主帐号,对应开官网发者主账号下的 ACCOUNT SID
    private $accountSid= '8a216da8635e621f01638c444b5b1758';


    //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
    private $accountToken= '8592729b77f443c0a766b1dc94a6e55d';

    //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
    //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
    private $appId = '8a216da8635e621f01638c444bab175e';

    //请求地址
    //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
    //生产环境（用户应用上线使用）：app.cloopen.com
//    private $serverIP='sandboxapp.cloopen.com';
    private $serverIP='app.cloopen.com';


    //请求端口，生产环境和沙盒环境一致
    private $serverPort='8883';

    //REST版本号，在官网文档REST介绍中获得。
    private $softVersion='2013-12-26';


//Demo调用
//**************************************举例说明***********************************************************************
//*假设您用测试Demo的APP ID，则需使用默认模板ID 1，发送手机号是13800000000，传入参数为6532和5，则调用方式为           *
//*result = sendTemplateSMS("13800000000" ,array('6532','5'),"1");																		  *
//*则13800000000手机号收到的短信内容是：【云通讯】您使用的是云通讯短信模板，您的验证码是6532，请于5分钟内正确输入     *
//*********************************************************************************************************************

    /**
     * 发送短信并设置session
     * @param string $phone
     * @param string $sessionKey
     * @param int    $tempId
     * @param int    $time
     *
     * @return array|bool|string
     */
    public function setCode($phone = '', $sessionKey = 'registerCode', $tempId = 1, $time = 5) {
        try {
            $code = rand(100000, 999999);
            //校验时间间隔
            $session = \Yii::$app->getSession();
            $lastData = $session->get($sessionKey);
            if (!empty($lastData) && isset($lastData['send_time'])) {
                if ($lastData['send_time'] + 60 > time()) {
                    throw new Exception('时间间隔小于60秒', 2000);
                }
            }
            $res = $this->sendTemplateSMS($phone, [$code, $time], $tempId);
            if ($res['status'] != true) {
                throw new Exception($res['msg'], $res['code']);
            }
            $session = \Yii::$app->getSession();
            $data = [
                'code' => $code,
                'expire_time' => time() + $time * 60,
                'send_time' => time(),
                'phone' => $phone
            ];
            $session->set($sessionKey, $data);
            return Functions::formatJson(1000, '发送成功', $data);
        } catch (Exception $e) {

            return Functions::formatJson($e->getCode(), $e->getMessage());
        }
    }

    /**
     * 发送模板短信
     * @param string $to 手机号码集合,用英文逗号分开
     * @param array  $datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
     * @param int    $tempId 模板Id
     *
     * @return array|string
     */
    function sendTemplateSMS($to = '',$datas = [],$tempId = 0)
    {
        try {
            // 初始化REST SDK
            $rest = new CCPRestSDK($this->serverIP, $this->serverPort, $this->softVersion);
            $rest->setAccount($this->accountSid,$this->accountToken);
            $rest->setAppId($this->appId);
            //信息显示
            $result = $rest->sendTemplateSMS($to,$datas,$tempId);

            if($result == NULL ) {
                throw new Exception("result error!");
            }
            if($result->statusCode != 0) {
                throw new Exception($result->statusMsg);
            }
            return ['status' => true];
        } catch(Exception $e) {
            return ['status' => false, 'msg' => $e->getMessage(), 'code' => $e->getCode()];
        }
    }



}
