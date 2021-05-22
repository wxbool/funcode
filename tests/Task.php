<?php

function readFilename ($name) {
    return iconv('gbk', 'utf-8', $name);
}
function writeFilename ($name) {
    return iconv('utf-8', 'gbk', $name);
}

function img_base64($path){
    //对path进行判断，如果是本地文件就二进制读取并base64编码，如果是url,则返回
    $img_data="";
    if (substr($path,0,strlen("http")) === "http"){
        $img_data = $path;
    }else{
        if($fp = fopen($path, "rb", 0)) {
            $binary = fread($fp, filesize($path)); // 文件读取
            fclose($fp);
            $img_data = base64_encode($binary); // 转码
        }else{
            printf("%s 图片不存在",$img_path);
        }
    }
    return $img_data;
}




class Task {

    public function __construct()
    {}

    public function run()
    {
        $targetDir = "H:\临时文件\高级健康管理师学员报考资料（从化伍湖）";

        $context = [];
        $this->readDir($targetDir , true , function ($path , $name) use ($context) {
            $name = readFilename($name);
            if (stripos($name , "身份证反面") !== false) {
                var_dump("反面跳过：" . writeFilename($name));
                return;
            }

            $data = $this->getInfo($path);
            if ($data === false) {
                return;
            }
            $str = implode("  " , [$data['name'] , $data['address']]);


            file_put_contents("result2.txt" , $str . "\r\n" , FILE_APPEND);
        });


//        $this->readDir($targetDir , true , function ($path , $name) {
//            if (stripos(readFilename($name) , "身份证反面") !== false) {
//                var_dump("反面跳过：" . writeFilename($name));
//                return;
//            }
//
//            $targetfile =  writeFilename("H:\临时文件\考生扫描件222/" ) . str_replace(["\\" , "/" , ":"] , "-" , $path) . "-" . $name;
//            var_dump(($targetfile));
//
//            if (!copy($path , $targetfile)) {
//                var_dump("copy fail.");
//            }
//        });
    }


    /**
     * @param $img_path
     * @return array|bool
     */
    protected function getInfo($img_path)
    {
        $url = "http://dm-51.data.aliyun.com/rest/160601/ocr/ocr_idcard.json";
        $appcode = "419786bb4e8745f4bfad0d5e4e5af9a5";
//        $img_path = "图片本地路径或者url";
        $method = "POST";

        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        //根据API的要求，定义相对应的Content-Type
        array_push($headers, "Content-Type".":"."application/json; charset=UTF-8");

        //如果没有configure字段，config设为空
        $config = array(
            "side"=>"face"
        );

        $img_data=img_base64($img_path);
        $request = array(
            "image" => "$img_data"
        );
        if(count($config) > 0){
            $request["configure"] = json_encode($config);
        }
        $body = json_encode($request);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        if (1 == strpos("$".$url, "https://")){
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        $result = curl_exec($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $rheader = substr($result, 0, $header_size);
        $rbody = substr($result, $header_size);

        $httpCode = curl_getinfo($curl,CURLINFO_HTTP_CODE);
        if($httpCode == 200){
            $result_str = $rbody;

            printf("result is :\n %s\n", writeFilename($result_str));

            $result = json_decode($result_str , true);
            if (!is_array($result)) {
                return false;
            }
            return [
                "address" => $result['address'],
                "name" => $result['name'],
            ];
        }else{
            printf("Http error code: %d\n", $httpCode);
            printf("Error msg in body: %s\n", writeFilename($rbody));

            return false;
        }
    }


    protected function readDir ($dir , $f = true , $callback = null)
    {
        if ($f) {
            $dir = writeFilename($dir);
        }
        if(is_dir($dir)){
            if ($dh = opendir($dir)) {
                while (($file= readdir($dh)) !== false){
                    if((is_dir($dir."/".$file)) && $file!="." && $file!=".."){
                        $this->readDir($dir."/".$file."/" , false , $callback);
                    } else{
                        if($file!="." && $file!=".."){
                            if (stripos(readFilename($file) , "身份证正面") !== false) {
                                if ($callback && $callback instanceof \Closure) {
                                    $callback($dir.$file , $file);
                                }
                                continue;
                            }
                            if (stripos(readFilename($file) , "身份证") !== false) {
                                if ($callback && $callback instanceof \Closure) {
                                    $callback($dir.$file , $file);
                                }
                                continue;
                            }

                        }
                    }
                }
                closedir($dh);
            }
        }
    }
}

error_reporting(E_ALL&~E_NOTICE);

$task = new Task();

$task->run();
