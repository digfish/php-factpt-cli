<?php

require "vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;
use digfish\factptclient\FactPtClient;
use digfish\factptclient\elements\Customer;
use digfish\factptclient\elements\Product;


class FactPtClientTest extends TestCase {

	static $new_customer_id;
	static $new_product_id;
	static $new_invoice_id;
	static $faker;

	var $client;

	protected function setUp(): void {
		$dotenv = Dotenv::createImmutable(__DIR__);
		$dotenv->load();

		$this->client = new FactPtClient();
		self::$faker = Faker\Factory::create();
	}

	public function testConstruct() {
		$this->assertInstanceOf(FactPtClient::class, $this->client);
	}


	public function testListDocuments() {
		$documents = $this->client->listDocuments();
		$this->assertTrue(count($documents) > 0);
	}

	public function testListCustomers() {
		$customers = $this->client->listCustomers();
		$this->assertGreaterThan(0, count($customers));
	}

	public function testSearchCustomers() {
		$customers = $this->client->searchCustomers('Cristiano');
		$this->assertEquals($this->client->lastStatus(), 200);
	}

	public function testCreateCustomer() {
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

		$new_created_customer = $this->client->createCustomer(['client'=>$new_customer]);
		self::$new_customer_id = $new_created_customer->id;
		$this->assertNotNull($new_created_customer->id);
	}


	public function testGetCustomer() {
		$customer = $this->client->getCustomer(self::$new_customer_id);
		$this->assertEquals($customer->id, self::$new_customer_id);
	}


	public function testCreateProduct() {
		$new_product = new Product();
		#$new_product->name = 'One night';
		$new_product->description = 'One night in double bed room';
		$new_product->price = 50.00;
		#$new_product->taxId = 25;
		$new_product->reference = self::$faker->asciify('**');
		$new_product->retention = FALSE;
		$new_product->type = 'service';
		$new_product->unitId = 1;
		$new_created_product = $this->client->createProduct(['product'=>$new_product]);
		self::$new_product_id = $new_created_product->id;
		$this->assertNotNull($new_created_product->id);
	}

	public function testGetProduct() {
		$product = $this->client->getProduct(self::$new_product_id);
		$this->assertEquals($product->id,self::$new_product_id);
	}

	function testListProducts() {
		$products = $this->client->listProducts();
		$this->assertEquals($this->client->lastStatus(),200);
	}

	function testSearchProducts() {
		$products = $this->client->searchProducts('PP');
		$this->assertEquals($this->client->lastStatus(), 200);
	}

	function testListTaxes()
	{
		$products = $this->client->listTaxes();
		$this->assertEquals($this->client->lastStatus(), 200);
	}

	function testGetTax() {
		$tax = $this->client->getTax(503);
		$this->assertEquals($tax->id,503);
	}

	function testSearchTax() {
		$tax = $this->client->searchTax('reduzida');
		$this->assertStringContainsString('reduzida', $tax->description);
	}


	function testCreateInvoice() {
		$new_created_invoice = $this->client->createInvoice(
			self::$new_customer_id,
			[['id' => self::$new_product_id]]);
		self::$new_invoice_id = $new_created_invoice->data->id;
		$this->assertNotNull($new_created_invoice->data->id);
	}

	function testGetInvoice() {
		$invoice = $this->client->getDocument(self::$new_invoice_id);
		$this->assertEquals($invoice->id,self::$new_invoice_id);
	}
}
