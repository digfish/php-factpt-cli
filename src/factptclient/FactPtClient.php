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
        #putenv('HTTP_PROXY=localhost:8888');
        $dotenv = Dotenv::createImmutable(".");
        $dotenv->load(); 
    }

    protected function _invoke($uri, $http_met = 'GET', $data = [])
    {
        $resp = null;
        try {

            $resp = $this->client->request($http_met, $uri, [
                'headers' => [
                    'x-auth-token' => $_ENV['FACTPT_TEST_API_KEY'],
                    'Content-type' => 'application/json',
                    'api-version' => '1.0.0'
                ],
                'body' => json_encode($data)
		       
            ]);
        } catch (ClientException $e) {
            $this->_dumpError($e);
            $resp = $e->getResponse();
            $this->lastResponse = $resp;
            return $resp;
        }
		#print($resp->getBody()->getContents());
        $this->lastResponse = $resp;
        $parsed_response = json_decode($resp->getBody()->getContents());
        return $parsed_response->AppResponse->data;
    }

    public function lastStatus() {
        return $this->lastResponse->getStatusCode();
    }

    protected function _dumpError($excpetion) {
    	echo ($excpetion->getMessage());
		echo ($excpetion->getResponse()->getBody()->getContents());
    }

    public function listDocuments() {
        return $this->_invoke('/documents');
    }

    public function createCustomer($new_customer) {
    	return $this->_invoke('/clients','POST',$new_customer);
    }

    public function getCustomer($customer_id) {
		return $this->_invoke('/clients/'.$customer_id);
	}

	public function listCustomers() {
		return $this->_invoke('/clients');
	}

    public function createProduct($new_product) {
        return $this->_invoke('/products','POST',$new_product);
    }

    public function getProduct($product_id) {
        return $this->_invoke('/products/'.$product_id);
    }

    public function listProducts() {
        return $this->_invoke('/products');
    }

    public function createInvoice($client_id,$items=[],$reference='') {
        return $this->_invoke('/documents/invoice','POST',[
            'client' => [ 'id'=> $client_id],
            'document' => [
                'date' => date('Y-m-d'),
                'paymentTerm' => 0,
                'reference' => $reference
            ],
            'items' => [$items]
        ]);
    }

    function getDocument($invoice_id) {
        return $this->_invoke('/documents/'.$invoice_id);
    }


}

/*$factpt = new FactPtClient();

print_r($factpt->listDocuments());

$new_customer = new Customer();
$new_customer->name = 'Samuel Viana';
$new_customer->tin = 123456789;
$new_customer->forceTin = TRUE;
$new_customer->address = 'Rua da Paz, nº 1';
$new_customer->zip = '8800-591';
$new_customer->city = 'Porto';
$new_customer->ric = TRUE;
$new_customer->retention = FALSE;
$new_customer->country = 'PT';
$new_customer->brand = 'Smoobu';
$new_customer->email = 'sam_viana@sapo.pt';
$new_customer->site = 'http://digfish.org';
$new_customer->phone = '912345678';
$new_customer->finalConsumer = FALSE;


$new_customer_id = $factpt->createCustomer(['client'=>$new_customer]);
$new_customer_id = $new_customer_id->id;

$customer = $factpt->getCustomer($new_customer_id);
print_r($customer);*/
