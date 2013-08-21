<?php

namespace Omnipay\GPwebpay\Message;

use Omnipay\TestCase;

/**
 * Description of Request
 *
 * @author Josef Macháček <josef.machacek@profimedia.cz>
 */
class RequestTest extends TestCase
{

	private $object;
	private $httpRequest;

	/* private $supportedCurrencies = array(
	  "CZK" => 203,
	  "USD" => 840,
	  "EUR" => 978,
	  "GBP" => 826
	  ); */

	public function setUp()
	{
		parent::setUp();
		$this->httpRequest = $this->getMockBuilder("\Symfony\Component\HttpFoundation\Request")->getMock();
		$this->object = new Request($this->getHttpClient(), $this->httpRequest);
	}

	/**
	 * @expectedException \Omnipay\Common\Exception\RuntimeException
	 */
	public function testGetDataBadCurrency()
	{
		$parameters = array('amount' => 10, 'currency' => 'hujer', 'returnUrl' => "sd", 'transactionId' => "sdf", 'returnUrl' => "sdf");
		$this->object->initialize($parameters);
		$this->object->getData();
	}

	public function testGetData()
	{
		$privateKey = __DIR__ . "/../TestKeys/testprivate.pem";
		$parameters = array('privateKeyPemPath' => $privateKey, 'privateKeyPassword' => 'hujer', 'amount' => 10, 'currency' => 'CZK', 'returnUrl' => "sdddd", 'transactionId' => "sdf", 'returnUrl' => "sdf");
		$this->object->initialize($parameters);

		$params = $this->object->getData();
		$this->assertTrue(isset($params['DIGEST']) && !empty($params['DIGEST']));
	}

	public function testSend()
	{
		$privateKey = __DIR__ . "/../TestKeys/testprivate.pem";
		$parameters = array('privateKeyPemPath' => $privateKey, 'privateKeyPassword' => 'hujer', 'amount' => 10, 'currency' => 'CZK', 'returnUrl' => "sdddd", 'transactionId' => "sdf", 'returnUrl' => "sdf");
		$this->object->initialize($parameters);

		$this->assertInstanceOf("\Omnipay\GPwebpay\Message\Response", $this->object->send());
	}

	public function testParameters()
	{
		$amount = 10;
		//gpwebay doesn't have decimal pointer
		$gpAmount = $amount*100;
		$currency = "CZK";
		$depositflag = 1;
		$returnUrl = "blabla";
		$merchantID = 123;
		$operation = "cut";
		$value = "sdsdfsdf";
		$path = "sfsfss";
		$password = "meteleskublesku";
		$pathpublic = "sdsfs";
		$pathPGW = "dsfsdf";
				
		$this->object->setAmount($amount);
		$this->assertSame($gpAmount, $this->object->getAmount());
		$this->object->setCurrency($currency);
		$this->assertSame($currency, $this->object->getCurrency());
		$this->object->setDepositflag($depositflag);
		$this->assertSame($depositflag, $this->object->getDepositflag());
		$this->object->setReturnUrl($returnUrl);
		$this->assertSame($returnUrl, $this->object->getReturnUrl());
		$this->object->setMerchantID($merchantID);
		$this->assertSame($merchantID, $this->object->getMerchantID());
		$this->object->setOperation($operation);
		$this->assertSame($operation, $this->object->getOperation());
		$this->object->setTransactionId($value);
		$this->assertSame($value, $this->object->getTransactionId());
		$this->object->setPrivateKeyPemPath($path);
		$this->assertSame($path, $this->object->getPrivateKeyPemPath());
		$this->object->setPrivateKeyPassword($password);
		$this->assertSame($password, $this->object->getPrivateKeyPassword());
		$this->object->setBankPublicKeyPemPath($pathpublic);
		$this->assertSame($pathpublic, $this->object->getBankPublicKeyPemPath());
		$this->object->setPgwInstructionsFile($pathPGW);
		$this->assertSame($pathPGW, $this->object->getPgwInstructionsFile());
	}

}
