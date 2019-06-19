# funcode
在这里，会整理创建一些好玩的接口。持续更新，敬请关注

目前，已经整合的接口如下：
* 阿里云·读光（图片文本识别）


# 1. 环境要求
* PHP version >= 5.6（推荐 7.1）
* PHP 扩展: curl, json, [`v8js`](https://www.php.net/manual/zh/book.v8js.php)


# 2. 安装

使用 composer:

$ composer require wxbool/funcode dev-master


# 3. 使用

## 获取全局实例

```
$app = \funcode\App::instance();
```
$app 是所有应用对象的集合，全局统一使用 $app 进行获取应用实例。


## 调用示例

### 1. ocr图片文本识别（[`阿里云-读光`](https://duguang.aliyun.com/experience?type=doc&midtype=fulldoc&subtype=contract)）

```
//获得 读光 应用
$duguang = $app->duguang;

//请求图片识别

//全文识别高精版识别
$response = $duguang->general('https://img.alicdn.com/tfs/TB1GHXrXzDpK1RjSZFrXXa78VXa-912-1128.jpg');
//英文专项识别
$response = $duguang->eng('https://img.alicdn.com/tfs/TB1GHXrXzDpK1RjSZFrXXa78VXa-912-1128.jpg');
//网络电商图片识别
$response = $duguang->ecommerce('https://img.alicdn.com/tfs/TB1GHXrXzDpK1RjSZFrXXa78VXa-912-1128.jpg');

if ($response) {
    //输出识别的文本（不换行）
    $response->content();
    //输出识别的文本（换行）
    $response->contentWrap();
}

```