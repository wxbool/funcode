<?php

namespace funcode\helper;

/**
 * Class Http
 * @package funcode\helper
 */
class Http {
    /**
     * @var bool 是否Post请求
     */
    protected $isPost = false;
    /**
     * @var string 请求Url
     */
    protected $url = '';
    /**
     * @var array 请求参数
     */
    protected $params = [];
    /**
     * @var bool 是否Https
     */
    protected $isHttps = false;
    /**
     * @var int 最长连接时间
     */
    protected $timeOut = 30;
    /**
     * @var int 最长请求时间
     */
    protected $connTimeOut = 30;
    /**
     * @var string UserAgent
     */
    protected $userAgent = '';
    /**
     * @var string Referer
     */
    protected $referer = '';
    /**
     * @var array Http Header 请求头
     */
    protected $httpHeader = [];
    /**
     * @var string 请求Cookies
     */
    protected $cookies = '';

    /**
     * @var 请求结果原始数据
     */
    protected $httpResult;
    /**
     * @var 请求信息
     */
    protected $httpInfo;

    /**
     * 设置请求Url
     * @param string $url
     */
    public function url($url='')
    {
        $this->url = $url;
    }

    /**
     * 设置请求数据
     * @param array $params
     */
    public function params(array $params = [])
    {
        $this->params = $params;
    }

    /**
     * @param bool $isPost
     */
    public function isPost($isPost = false)
    {
        $this->isPost = $isPost;
    }

    /**
     * @param string $userAgent
     */
    public function userAgent($userAgent = '')
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @param string $referer
     */
    public function referer($referer)
    {
        $this->referer = $referer;
    }

    /**
     * @param array $httpHeader
     */
    public function httpHeader($httpHeader = [])
    {
        $this->httpHeader = $httpHeader;
    }

    /**
     * @param string $cookies
     */
    public function cookies($cookies)
    {
        $this->cookies = $cookies;
    }

    /**
     * @param int $timeOut
     */
    public function timeOut($timeOut)
    {
        $this->timeOut = $timeOut;
    }

    /**
     * @param bool $isHttps
     */
    public function isHttps($isHttps = false)
    {
        $this->isHttps = $isHttps;
    }

    /**
     * @param int $connTimeOut
     */
    public function connTimeOut($connTimeOut = 30)
    {
        $this->connTimeOut = $connTimeOut;
    }



    /**
     * 开始请求
     * @return bool
     */
    public function request()
    {
        if (empty($this->url)) {
            return false;
        }
        $params = $this->params;
        if ($params && is_array($params)) {
            $params = http_build_query($params);
        }
        //init
        $thisCurl = curl_init();
        if (!empty($this->userAgent)) {
            curl_setopt($thisCurl, CURLOPT_USERAGENT, $this->userAgent);
        }
        curl_setopt($thisCurl, CURLOPT_CONNECTTIMEOUT, $this->connTimeOut);
        curl_setopt($thisCurl, CURLOPT_TIMEOUT, $this->timeOut);
        //Referer
        if ($this->referer) {
            curl_setopt ($thisCurl, CURLOPT_REFERER, $this->referer);
        }

        //请求头
        if ($this->httpHeader && is_array($this->httpHeader)) {
            curl_setopt($thisCurl, CURLOPT_HTTPHEADER, $this->httpHeader);
        }
        //cookies
        if (!empty($this->cookies)) {
            curl_setopt($thisCurl,CURLOPT_COOKIE , $this->cookies);
        }
        //https
        if ($this->isHttps) {
            curl_setopt($thisCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($thisCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            //CURL_SSLVERSION_TLSv1
            curl_setopt($thisCurl, CURLOPT_SSLVERSION, 1);
        }
        curl_setopt($thisCurl, CURLOPT_RETURNTRANSFER, 1);
        //Post
        if ($this->isPost) {
            curl_setopt($thisCurl, CURLOPT_POST ,true);
            curl_setopt($thisCurl, CURLOPT_URL, $this->url);
            if ($params) {
                var_dump($params);
                curl_setopt($thisCurl, CURLOPT_POSTFIELDS , $params);
            }
        } else {
            if ($params) {
                curl_setopt($thisCurl, CURLOPT_URL, $this->url . '?' . $params);
            } else {
                curl_setopt($thisCurl, CURLOPT_URL, $this->url);
            }
        }

        //代理
//        curl_setopt($thisCurl, CURLOPT_HTTPPROXYTUNNEL, true);
//        curl_setopt($thisCurl, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
//        curl_setopt($thisCurl, CURLOPT_PROXY, "61.128.208.94"); //代理服务器地址
//        curl_setopt($thisCurl, CURLOPT_PROXYPORT, '3128'); //代理服务器端口
//        //curl_setopt($ch, CURLOPT_PROXYUSERPWD, ":"); //http代理认证帐号，名称:pwd的格式
//        curl_setopt($thisCurl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式

        //TRUE 时追踪句柄的请求字符串，从 PHP 5.1.3 开始可用。允许你查看请求header
        curl_setopt($thisCurl, CURLINFO_HEADER_OUT, true);

        //执行
        $this->httpResult = curl_exec($thisCurl);
        $this->httpInfo   = curl_getinfo($thisCurl);

        if (curl_errno($thisCurl) != 0) {
            throw new \Exception('curl : ' . curl_error($thisCurl));
        }
        @curl_close($thisCurl);
        if (intval($this->httpInfo["http_code"]) == 200) {
            return true;
        }
        return false;
    }


    /**
     * 获取原始结果数据
     * @return mixed
     */
    public function getOriginalText()
    {
        return $this->httpResult;
    }


    /**
     * 对Http返回的结果数据进行文本转码
     * @param string $in_charset
     * @param string $out_charset
     */
    public function httpResultDecode($in_charset="gbk", $out_charset="utf-8")
    {
        $this->httpResult = Helper::arrayIconv($this->httpResult , $in_charset, $out_charset);
    }


    /**
     * 获取转JSON数据
     * @return mixed|null
     */
    public function getJsonDecodeData()
    {
        if (!$this->httpResult) return null;

        return json_decode($this->httpResult , true);
    }

    /**
     * 获取Http请求信息
     * @return mixed
     */
    public function getHttpInfo()
    {
        return $this->httpInfo;
    }
}