<?php

namespace digfish\factptclient;

require_once "vendor/autoload.php";

use GuzzleHttp\Client;
use Dotenv\Dotenv;
use GuzzleHttp\Exception\ClientException;


use \digfish\factptclient\elements\Product;
use \digfish\factptclient\elements\Customer;

class FactPtClient {

    private $client;
    protected $lastResponse;

    function __construct() {
        $this->client = new Client([
            'base_uri' => 'http://api.sandbox.fact.pt/',
            'timeout'  => 10.0,
        ]);
        putenv('HTTP_PROXY=localhost:8888');
        $dotenv = Dotenv::createImmutable(".");
        $dotenv->load(); 
    }


    protected function _invoke_page($uri,$query=[]) {
        $appResponse = $this->_invoke($uri,'GET',[],array_merge($query,['page'=>1]));
        $resp_data = $appResponse->data;
        while (isset($appResponse->next)) {
            parse_str(parse_url($appResponse->next,PHP_URL_QUERY),$toks);
            $appResponse = $this->_invoke($uri,'GET',[],['page'=>$toks['page']]);
            $resp_data = array_merge($resp_data,$appResponse->data);
        }
        return $resp_data;
    }

    protected function _invoke($uri, $http_met = 'GET', $input_data = [],$query=[],$headers=[])
    {
        $resp = null;

        $headers = array_merge($headers, [
                    'x-auth-token' => $_ENV['FACTPT_TEST_API_KEY'],
                    'Content-type' => 'application/json',
                    'api-version' => '1.0.0'
                ]
        );

        try {

            $resp = $this->client->request($http_met, $uri, [
                'headers' => $headers,
                'body' => json_encode($input_data),
                'query' => $query
            ]);
        } catch (ClientException $e) {
            $this->_dumpError($e);
            $resp = $e->getResponse();
            $this->lastResponse = $resp;
            return $resp;
        }
        $this->lastResponse = $resp;
        $parsed_response = json_decode($resp->getBody()->getContents());

        $appResponse = $parsed_response->AppResponse; 
        return $appResponse;
    }



    public function lastStatus() {
        return $this->lastResponse->getStatusCode();
    }

    protected function _dumpError($excpetion) {
    	echo ($excpetion->getMessage());
		echo ($excpetion->getResponse()->getBody()->getContents());
    }

    public function listDocuments() {
        return $this->_invoke_page('/documents');
    }

    public function createCustomer($new_customer) {
    	return $this->_invoke('/clients','POST',$new_customer)->data;
    }

    public function getCustomer($customer_id) {
		return $this->_invoke('/clients/'.$customer_id)->data;
	}

	public function listCustomers() {
		return $this->_invoke_page('/clients');
	}

    public function searchCustomers($q) {
        return $this->_invoke_page('/clients',['search'=>$q]);
    }

    public function createProduct($new_product) {
        return $this->_invoke('/products','POST',$new_product)->data;
    }

    public function getProduct($product_id) {
        return $this->_invoke('/products/'.$product_id)->data;
    }

    public function listProducts() {
        return $this->_invoke_page('/products');
    }

    public function searchProducts($q) {
        return $this->_invoke_page('/products',['search'=>$q]);
    }

    public function createInvoice($client_id,$items,$document=[]) {
        return $this->_invoke('/documents/invoice','POST',[
            'client' => [ 'id'=> $client_id],
            'document' => $document,
            'items' => $items
        ]);
    }

    function getDocument($invoice_id) {
        return $this->_invoke('/documents/'.$invoice_id)->data;
    }

    function listTaxes() {
        return $this->_invoke_page('/taxes');
    }


}

