<?php

class Test {

    protected $app;

    public function __construct()
    {
        require_once '../vendor/autoload.php';

        $this->app = \funcode\App::instance();
    }

    public function ocr()
    {
        try {
            $result = $this->app->duguang->general('https://img.alicdn.com/tfs/TB1GHXrXzDpK1RjSZFrXXa78VXa-912-1128.jpg');
            if (!$result) {
                return;
            }
        } catch (\funcode\exception\FuncodeException $e) {
            var_dump('error : ' . $e->getMessage());return;
        }

        var_dump($result->get());
        var_dump($result->content());
        var_dump($result->contentWrap());
    }
}

$tengxunAi = new Test();

$tengxunAi->ocr();
