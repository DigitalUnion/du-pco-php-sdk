<?php

namespace DigitalUnion\utils;

class Response
{
    /**
     * @var string
     * Code response code
     *
     * |code  | describe                        |
     * |------|---------------------------------|
     * |0     | success                         |
     * |10000 | IP not in whitelist             |
     * |10001 | Request path error              |
     * |10002 | Internal server error           |
     * |10100 | Param cilent_id required        |
     * |10101 | Param client_id not found       |
     * |10102 | This service is not activated   |
     * |10200 | Secret key required             |
     * |10201 | Secret not found                |
     * |10202 | Decode failed                   |
     * |10203 | Get request body failed         |
     * |10300 | Service not found               |
     * |10999 | Other error                     |
     *
     * code 0 means success, others means errors
     */
    public $code;

    /**
     * @var string
     */
    public $msg;

    /**
     * @var mixed
     */
    public $data;

    /**
     *
     */
    public function __construct($code, $msg, $data)
    {
        $this->code = $code;
        $this->msg = $msg;
        $this->data = $data;
    }
}