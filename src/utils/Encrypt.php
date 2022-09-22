<?php

namespace DigitalUnion\utils;

class Encrypt
{
    /**
     * @param $data string
     * @param $secretVal string
     * @return string
     */
    public function encode($data, $secretVal)
    {
        return $this->exclusiveOr(zlib_encode($data), $secretVal);
    }

    /**
     * @param $data string
     * @param $secretVal string
     * @return false|string
     */
    public function decode($data, $secretVal)
    {
        return zlib_decode($this->exclusiveOr($data, $secretVal));
    }

    /**
     * @param $data string
     * @param $secretVal string
     * @return string
     */
    function exclusiveOr($data, $secretVal)
    {
        $data = $this->str2bytes($data);
        $secretVal = $this->str2bytes($secretVal);
        $dataLen = count($data);
        $secretValLen = count($secretVal);

        $result = [];
        for ($i = 0; $i < $dataLen; $i++) {
            $result[] = $data[$i]^$secretVal[$i%$secretValLen];
        }
        return $this->bytes2str($result);
    }

    /**
     * @param array $bytes
     * @return string
     */
    public function bytes2str($bytes)
    {
        $str = '';
        foreach ($bytes as $ch) {
            $str .= chr($ch);
        }
        return $str;
    }

    /**
     * @param string $string
     * @return array
     */
    public function str2bytes($string)
    {
        $bytes = array();
        for ($i = 0; $i < strlen($string); $i++) {
            $bytes[] = ord($string[$i]);
        }
        return $bytes;
    }
}
