<?php
/**
 * Created by 迹忆.
 * User: onmpw
 * Home: https://www.onmpw.com
 * Date: 2018/12/19
 * Time: 13:25
 */

namespace Onmpw\JiyiRequest\Lib;


class CurlRequest extends RequestBase implements RequestContract
{
    /**
     * 响应请求
     *
     * @param $method
     * @param $param
     * @return mixed
     */
    public function getResponse($method,$param)
    {
        $postData = $this->setAccessMethod($method)->preparePostData($param);

        $response = self::curl($postData['host'],$postData['data']);

        return $response;

    }

    /**
     * 使用curl 进行请求
     *
     * @param $url
     * @param bool $params
     * @param string $type
     * @param string $protocol
     * @param string $headers
     * @return mixed
     */
    protected static function curl($url, $params = false, $type='post', $protocol = 'https',$headers = '')
    {
        $httpInfo = [];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if(!empty($headers)){
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        if ($protocol == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        }

        if ($type=='post') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($params)?http_build_query($params):$params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                if (is_array($params)) {
                    $params = http_build_query($params);
                }
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
//        dd($response);
        curl_close($ch);

        return json_decode(json_encode($response),true);
    }
}