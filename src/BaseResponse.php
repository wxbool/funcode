<?php

namespace funcode;

/**
 * Class BaseResponse
 * @package funcode
 */
class BaseResponse
{
    protected $_data = [];

    public function __construct($data = [])
    {
        if (is_array($data) && !empty($data)) {
            $this->_data = array_merge($this->_data , $data);
        }
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->_data[ $name ] = $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->_data[ $name ];
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->_data;
    }
}