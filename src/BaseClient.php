<?php

namespace DigitalUnion;

class BaseClient extends Client
{
    public function __construct($clientId, $secretKey, $secretVal)
    {
        parent::__construct($clientId, $secretKey, $secretVal);
        $this->domain = baseDomain;
    }
}