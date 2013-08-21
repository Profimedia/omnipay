<?php

namespace Omnipay\GPwebpay\Message;

use Omnipay\TestCase;

/**
 * Description of CompleteRequest
 *
 * @author Josef Macháček <josef.machacek@profimedia.cz>
 */
class CompleteRequestTest extends TestCase
{

	private $httpRequest;

	public function setUp()
	{
		parent::setUp();
		$this->httpRequest = $this->getMockBuilder("\Symfony\Component\HttpFoundation\Request")->getMock();
		$this->request = new CompleteRequest($this->getHttpClient(), $this->httpRequest);
	}

	/**
	 * @expectedException \Omnipay\Common\Exception\RuntimeException
	 */
	public function testGetDataNoDigestException()
	{
		$parameters = array();
		$this->request->initialize($parameters);
		$this->request->getData();
	}

	/**
	 * @expectedException \Omnipay\Common\Exception\RuntimeException
	 */
	public function testGetDataMissingParameter()
	{
		$this->httpRequest->expects($this->atLeastOnce())
						->method("get")
						->will($this->onConsecutiveCalls("abrakanadraka", false));
		$this->request->initialize(array());
		$this->request->getData();
	}
	
	/**
	 * @expectedException \Omnipay\Common\Exception\RuntimeException
	 */
	public function testGetDataBadPublicKey()
	{
		$this->httpRequest->expects($this->atLeastOnce())
						->method("get")
						->will($this->onConsecutiveCalls("abrakanadraka"));
		$this->request->initialize(array());
		$this->request->getData();
	}
	
	/**
	 * @expectedException \Omnipay\Common\Exception\RuntimeException
	 */
	public function testGetDataUnverifiedRequest()
	{
		$this->httpRequest->expects($this->atLeastOnce())
						->method("get")
						->will($this->onConsecutiveCalls("abrakanadraka"));
		$publicKey = __DIR__."/../TestKeys/testmuzopublic.pem";
		$this->request->initialize(array("bankPublicKeyPemPath"=>$publicKey));
		$this->request->getData();
	}

	public function testGetData()
	{
		$digest = "ZSlO5q/KkCyMDvUJ6dYwnCcvm9Jni4fIOnHa2toebZP9BQgM7DwgD2jNVa3bxV5uE2RRXGxp9PW6GgoIONL3b178xdVUOxk1gExNgkRNBOPjya5P/bWmGajj7tQFJ7wtuig6UmjUvukI9e+QuesvtzndPyJ+OYw2guIHIMp0shbTZR0ojl4AKVe5EMKwCZllI3Vu3pRmGyfHqyiGz4LgcaO6WI1bOD97TTupWY4hNofr46LiD6s7OocFIveYV7s/zJPW5si5lNy2Xpljog1zpyXNkYtXOOBdi9Za001LRm8HK1DpI/ys5y2fwSbP6RMhKLSLJYU1Bdixn1+ftX2mmw==";
		$this->httpRequest->expects($this->atLeastOnce())
						->method("get")
						->will($this->onConsecutiveCalls($digest, "CREATE_ORDER", 94, 94, 50, 0, "Drzitel karty zrusil platbu"));
		$publicKey = __DIR__."/../TestKeys/testmuzopublic.pem";
		$this->request->initialize(array("bankPublicKeyPemPath"=>$publicKey));
		$params = $this->request->getData();
	}
}
