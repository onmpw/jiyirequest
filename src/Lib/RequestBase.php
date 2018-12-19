<?php
/**
 * Created by 迹忆.
 * User: onmpw
 * Home: https://www.onmpw.com
 * Date: 2018/12/19
 * Time: 13:33
 */

namespace Onmpw\JiyiRequest\Lib;


class RequestBase
{
    const APP_ID = 1001;

    const APP_SECRET = '3270064d0afe3be41ae838cd9e667b1c';

    const HOST = '';

    private $postData = [];


    /**
     * 准备请求所用的参数
     *
     * @param $param
     * @return array
     */
    protected function preparePostData($param)
    {
        $this->setParam($param);

        $this->setPostData('sign',$this->createSign($this->postData));

        return $this->postData;
    }

    /**
     * 初始化参数
     *
     * @return $this
     */
    protected function buildPostData()
    {
        if(empty($this->postData)) {
            $this->postData = [
                'host' => env('HOST',self::HOST),
                'data' => [
                    'head' => '',
                    'app_id' => config('api.APP_ID',self::APP_ID),
                    'nonce' => md5(time()),
                    'ip' => ''
                ],
                'appSecret' => config('api.APP_SECRET',self::APP_SECRET)
            ];
        }

        return $this;
    }

    /**
     * 设置参数
     *
     * @param $param
     * @return $this
     */
    protected function setParam($param)
    {
        if(is_array($param)){
            foreach($param as $key=>$value){
                $this->setPostData($key,$value);
            }
        }
        return $this;
    }

    /**
     * 访问的方法
     * @param $method
     * @return $this
     */
    protected function setAccessMethod($method)
    {
        $this->buildPostData()->setPostData('method',$method);

        return $this;
    }

    /**
     * 设置 postData值
     * @param $key
     * @param $data
     */
    protected function setPostData($key,$data)
    {
        $this->postData['data'][$key] = is_array($data)?json_encode($data):$data;
    }

    /**
     * 创建数字校验签名
     * @param $postData
     * @return string
     */
    protected function createSign($postData)
    {
        if(isset($postData['data']['sign'])) unset($postData['data']['sign']);

        $appSecret = $postData['appSecret'];
        $data = $postData['data'];

        $sign = $appSecret;
        ksort($data);
        foreach ($data as $key => $val) {
            $sign .= $key . $val;
        }
        $sign .= $appSecret;
        $sign = strtoupper(md5($sign));

        return $sign;
    }
}