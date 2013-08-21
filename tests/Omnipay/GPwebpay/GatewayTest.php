<?php

namespace Omnipay\GPwebpay;

use Omnipay\TestCase;

/**
 * Gateway for GPwebpay (paymuzo)
 *
 * @author Josef Macháček <josef.machacek@profimedia.cz>
 */
class GatewayTest extends TestCase
{

	private $object;

	public function setUp()
	{
		parent::setUp();
		$this->object = new Gateway($this->getHttpClient(), $this->getHttpRequest());
	}

	public function testGetName()
	{
		$this->assertSame("GPwebpay", $this->object->getName());
	}

	public function testGetDefaultParameters()
	{
		$params = $this->object->getDefaultParameters();
		$this->assertTrue(isset($params['depositflag']));
	}

	public function testPurchase()
	{
		$parameters = array();
		$request = $this->object->purchase($parameters);
		$this->assertInstanceOf("\Omnipay\GPwebpay\Message\Request", $request);
	}

	public function testCompletePurchase()
	{
		$parameters = array();
		$request = $this->object->completePurchase($parameters);
		$this->assertInstanceOf("\Omnipay\GPwebpay\Message\CompleteRequest", $request);
	}
	
	public function testGetTransactionDetails()
	{
		$parameters = array();
		$request = $this->object->getTransactionDetails($parameters);
		$this->assertInstanceOf("\Omnipay\GPwebpay\Message\GetTransactionDetailsRequest", $request);
	}

	public function testSetMerchantID()
	{
		$this->object->setMerchantID(123);
		$this->assertSame(123, $this->object->getMerchantID());
	}

	public function testSetPrivateKeyPemPath()
	{
		$this->object->setPrivateKeyPemPath("123");
		$this->assertSame("123", $this->object->getPrivateKeyPemPath());
	}

	public function testSetPrivateKeyPassword()
	{
		$this->object->setPrivateKeyPassword("secret");
		$this->assertSame("secret", $this->object->getPrivateKeyPassword());
	}
	
	public function testSetBankPublicKeyPemPath()
	{
		$this->object->setBankPublicKeyPemPath("aaa");
		$this->assertSame("aaa", $this->object->getBankPublicKeyPemPath());
	}

	public function testSetPgwInstructionsFile()
	{
		$this->object->setPgwInstructionsFile("aaa");
		$this->assertSame("aaa", $this->object->getPgwInstructionsFile());
	}
}
