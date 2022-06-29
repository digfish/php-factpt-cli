<?php

namespace digfish\factptclient\elements;

class Product
{
    #public $name;
    public $description;
    public $price;
    public $reference;
    public $retention;
    public $type;
    public $unitId;
}
/*
  "product":{
    "description":"Test to the product creation",
    "price":10.00,
    "reference":"723",
    "retention":true,
    "taxId":25,
    "type":"service",
    "unitId":1,
    "allowSerialNumber":false
  }
*/
