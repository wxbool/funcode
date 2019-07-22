<?php

namespace funcode\server\ocr;

use funcode\BaseServer;
use funcode\Exception\FuncodeException;
use funcode\helper\Http;
use funcode\response\ocr\General;


/**
 * Class Duguang
 * @package funcode\server\ocr
 */
class Duguang extends BaseServer {

    /**
     * @var string
     */
    protected $ocrapi = 'https://duguang.aliyun.com/ocrdemo/ocrDemoSecondService.json';


    /**
     * 全文识别高精版
     * @param $image
     * @return General|null
     * @throws FuncodeException
     */
    public function general($image)
    {
        return $this->request($image , 'general');
    }

    /**
     * 英文专项识别
     * @param $image
     * @return General|null
     * @throws FuncodeException
     */
    public function eng($image)
    {
        return $this->request($image , 'eng');
    }

    /**
     * 网络电商图片
     * @param $image
     * @return General|null
     * @throws FuncodeException
     */
    public function ecommerce($image)
    {
        return $this->request($image , 'ecommerce');
    }








    /**
     * @param $image
     * @return General|null
     * @throws FuncodeException
     */
    protected function request($image , $type = 'general')
    {
        if (!$image || empty($image)) {
            return null;
        }

        //params
        $params = $this->imageParams($image , $type);
        if (!$params) {
            return null;
        }

        //开始请求
        $thisCurl = new Http();
        $thisCurl->url($this->ocrapi);
        $thisCurl->httpHeader([
            ':authority:duguang.aliyun.com',
            ':method:POST',
            ':path:/ocrdemo/ocrDemoSecondService.json',
            ':scheme:https',
            'accept:application/json, text/plain, */*',
            'content-type:application/x-www-form-urlencoded',
            'origin:https://duguang.aliyun.com'
        ]);
        $thisCurl->referer('https://duguang.aliyun.com/experience?type=doc&midtype=fulldoc&subtype=contract');
        $thisCurl->params($params);
        $thisCurl->userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36');
        $thisCurl->isHttps(true);
        //Post
        $thisCurl->isPost(true);
        $thisCurl->timeOut(12);
        $thisCurl->connTimeOut(12);
        //请求
        $code = $thisCurl->request();
        if (!$code) {
            return null;
        }
        //结果转码
        $httpResult = $thisCurl->getJsonDecodeData();
        if ($httpResult['code'] != 200) {
            throw new FuncodeException('Duguang : ' . $httpResult['message']);
        }
        //return
        return new General($httpResult['data']);
    }


    /**
     * @param $image
     * @param string $type
     * @return array|null
     * @throws FuncodeException
     */
    protected function imageParams($image , $type = '')
    {
        $imageGenre = $this->imageGenre($image);

        $data = [];
        switch ($imageGenre) {
            case 'http':
                $data['url'] = $image;
                break;
            case 'base64':
                $data['img'] = $image;
                break;
            default:
                return null;
        }

        $type = 'demo_' . $type;
        $v8 = \funcode\helper\V8::instance();
        $sign = $v8->run(function () use ($imageGenre , $image , $type) {
            $encodeJs = __DIR__ . '/encode.js';
            if (!is_file($encodeJs)) {
                return;
            }
            $vendor = file_get_contents($encodeJs);
            $script = <<<SCRIPT

var imageGenre = '{$imageGenre}';    
var image = '{$image}';
var type = '{$type}';
var duguang = 'duguang.aliyun.com';
    
if (imageGenre == 'http') {
    var code = encodeURI(image) + duguang + type;
} else {
    var code = image.length + duguang + type;
}

var result = encode(code);

result;
SCRIPT;
            return $vendor . $script;
        });

        $data['type'] = $type;
        $data['sign'] = $sign;
        $data['prob'] = 'true';
        $data['charInfo'] = 'true';
        return $data;
    }


    /**
     * @param $image
     * @return string
     */
    protected function imageGenre($image)
    {
        if (strripos($image , 'http:') !== false || strripos($image , 'https:') !== false) {
            return 'http';
        } else if (strripos($image , ';base64') !== false) {
            return 'base64';
        }

        return '';
    }
}