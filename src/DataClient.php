<?php

namespace DigitalUnion;

class DataClient extends Client
{
    public function __construct($clientId, $secretKey, $secretVal)
    {
        parent::__construct($clientId, $secretKey, $secretVal);
        $this->domain = dataDomain;
    }
}