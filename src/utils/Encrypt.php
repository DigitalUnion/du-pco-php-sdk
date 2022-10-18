<?php

namespace DigitalUnion\utils;

class Encrypt
{
    /**
     * @param $data string
     * @param $key string should be the AES key, 16 bytes
     * @return false|string
     */
    public function encode($data, $key)
    {
        return $this->aesEncrypt($this->str2bytes(zlib_encode($data, ZLIB_ENCODING_DEFLATE)), $this->str2bytes($key));
    }

    /**
     * @param $data string
     * @param $key string should be the AES key, 16 bytes
     * @return false|string
     */
    public function decode($data, $key)
    {
        return zlib_decode($this->bytes2str($this->aesDecrypt($data, $this->str2bytes($key))));
    }

    /**
     * @param $data array bytes
     * @param $key array bytes
     * @return false|string
     */
    private function aesEncrypt($data, $key)
    {
        $filledKey = $this->fillKey($key);
        $key = $this->bytes2str($filledKey);
        return openssl_encrypt($this->bytes2str($this->pkcs5Padding($data)),
            $this->getAesAlgo(count($filledKey)), $key, OPENSSL_RAW_DATA, substr($key, 0, 16));
    }

    /**
     * @param $data string
     * @param $key array bytes
     * @return array
     */
    private function aesDecrypt($data, $key)
    {
        $filledKey = $this->fillKey($key);
        $key = $this->bytes2str($filledKey);
        return $this->str2bytes(openssl_decrypt($data,
            $this->getAesAlgo(count($filledKey)), $key, OPENSSL_RAW_DATA, substr($key, 0, 16)));
    }

    /**
     * @param $length
     * @return string
     */
    private function getAesAlgo($length)
    {
        if ($length <= 16) {
            return 'aes-128-cbc';
        } elseif ($length >= 17 && $length <= 24) {
            return 'aes-192-cbc';
        }
        return 'aes-256-cbc';
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

    /**
     * @param $data array bytes
     * @return array bytes
     */
    private function pkcs5Padding($data)
    {
        $padding = 16 - count($data) % 16;
        $padText = [];
        for ($i = 0; $i < $padding; $i++) {
            $padText[] = $padding;
        }
        return array_merge($data, $padText);
    }

    /**
     * @param $data array bytes
     * @return array bytes
     */
    private function pkcs5UnPadding($data)
    {
        $len = count($data);
        if ($len == 0) {
            return $data;
        }
        $unPadding = (int)$data[$len-1];
        return array_slice($data, 0, $len - $unPadding);
    }

    /**
     * @param $key array bytes
     * @return array bytes
     */
    private function fillKey($key)
    {
        $len = count($key);
        if ($len == 16) {
            return $key;
        }
        if ($len < 16) {
            return $this->fillN($key, 16);
        }
        return array_slice($key, 0, 16);
    }

    /**
     * @param $s array bytes
     * @param $count integer
     * @return array bytes
     */
    private function fillN($s, $count)
    {
        $len = count($s);
        $div = intval(floor($count / $len));
        $mod = $count % $len;
        $arrayBytes = [];
        for ($i = 0; $i < $div; $i++) {
            $arrayBytes = array_merge($arrayBytes, $s);
        }
        if ($mod > 0) {
            $arrayBytes = array_merge($arrayBytes, array_slice($s, 0, $mod));
        }
        return $arrayBytes;
    }
}
