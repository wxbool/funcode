<?php
namespace funcode;

use funcode\config\Config;


/**
 * Class App
 * @package funcode
 *
 * @property \funcode\server\ocr\Duguang $duguang
 */
class App {

    protected static $thisInstance;

    /**
     * @var array
     */
    protected $providers = [
        'duguang' => '\\funcode\\server\\ocr\\Duguang',
    ];

    /**
     * @var array
     */
    protected $instance = [];

    /**
     * @return App
     */
    public static function instance()
    {
        if (!self::$thisInstance) {
            self::$thisInstance = new self();
        }

        return self::$thisInstance;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (empty($name)) return null;
        if (!array_key_exists($name , $this->providers)) {
            return null;
        }
        //实例化
        if (!array_key_exists($name , $this->instance)) {
            $this->instance[$name] = new $this->providers[$name];
        }
        return $this->instance[$name];
    }
}