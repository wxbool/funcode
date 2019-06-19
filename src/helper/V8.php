<?php

namespace funcode\helper;

use funcode\Exception\FuncodeException;

/**
 * Class V8
 * @package funcode\helper
 */
class V8 {

    /**
     * @var V8
     */
    protected static $istance;

    /**
     * @var \V8Js
     */
    protected $v8;

    /**
     * @var int
     */
    protected $time_limit = 0;

    /**
     * @var int
     */
    protected $memory_limit = 0;

    /**
     * @var int
     */
    protected $flag = \V8Js::FLAG_NONE;


    public function __construct()
    {
        $this->v8 = new \V8Js();
    }

    /**
     * @return V8
     */
    public static function instance()
    {
        if (!self::$istance) {
            self::$istance = new self();
        }

        return self::$istance;
    }


    /**
     * @param int $flag
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    }

    /**
     * @param int $time_limit
     */
    public function setTimeLimit($time_limit)
    {
        $this->time_limit = $time_limit;
    }

    /**
     * @param int $memory_limit
     */
    public function setMemoryLimit($memory_limit)
    {
        $this->memory_limit = $memory_limit;
    }


    /**
     * @param $javascript
     * @param string $identifier
     * @return mixed
     * @throws FuncodeException
     */
    public function run($javascript , $identifier = '')
    {
        if ($javascript instanceof \Closure) {
            $javascript = $javascript();
        }

        try {
            $result = $this->v8->executeString($javascript ? $javascript : '' , $identifier , $this->flag , $this->time_limit , $this->memory_limit);
        } catch (\V8JsScriptException $e) {
            throw new FuncodeException($e->getMessage() , $e->getCode());
        } catch (\V8JsTimeLimitException $e) {
            throw new FuncodeException($e->getMessage() , $e->getCode());
        } catch (\V8JsScriptException $e) {
            throw new FuncodeException($e->getMessage() , $e->getCode());
        }

        return $result;
    }
}