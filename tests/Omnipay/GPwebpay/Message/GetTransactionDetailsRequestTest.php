<?php

namespace Omnipay\GPwebpay\Message;

use Omnipay\TestCase;

/**
 * Description of GetTransactionDetailsRequest
 *
 * @author Josef Macháček <josef.machacek@profimedia.cz>
 */
class GetTransactionDetailsRequestTest extends TestCase
{
	private $httpRequest;

	public function setUp()
	{
		parent::setUp();
		$this->httpRequest = $this->getMockBuilder("\Symfony\Component\HttpFoundation\Request")->getMock();
		$this->request = new GetTransactionDetailsRequest($this->getHttpClient(), $this->httpRequest);
	}
	
	/**
	 * @expectedException \Omnipay\Common\Exception\InvalidRequestException
	 */
	public function testGetDataNoTransactionID()
	{
		$parameters = array();
		$this->request->initialize($parameters);
		$this->request->getData();
	}
	
	/**
	 * @expectedException Omnipay\Common\Exception\RuntimeException
	 */
	public function testGetDataBadKey()
	{
		$privateKey = __DIR__."/../TestKeys/testprivate.pem";
		$this->request->initialize(array("privateKeyPemPath"=>$privateKey, "transactionId"=>"dasfsd"));
		$this->request->getData();
	}
	
	public function testGetData()
	{
		$privateKey = __DIR__."/../TestKeys/testprivate.pem";
		$this->request->initialize(array("privateKeyPemPath"=>$privateKey, "privateKeyPassword"=>"hujer", "transactionId"=>"dasfsd"));
		$this->request->getData();
	}
	
	public function testSend()
	{
		$privateKey = __DIR__."/../TestKeys/testprivate.pem";
		$soap = $this->getMockBuilder("\SoapClient")
						->disableOriginalConstructor()
						->setMethods(array("queryOrderState"))
						->getMock();
		$this->request->setSoapClient($soap);
		$this->request->initialize(array("transactionId"=>"dfsdf", "merchantID"=>"123","privateKeyPemPath"=>$privateKey, "privateKeyPassword"=>"hujer"));
		$this->assertInstanceOf("\Omnipay\GPwebpay\Message\GetTransactionDetailsResponse", $this->request->send());
	}
	
	public function testSetSoapClient()
	{
		$soap = $this->getMockBuilder("\SoapClient")->disableOriginalConstructor()->getMock();
		$this->assertInstanceOf("Omnipay\GPwebpay\Message\GetTransactionDetailsRequest", $this->request->setSoapClient($soap));
	}
	
	/**
	 * @expectedException Omnipay\Common\Exception\RuntimeException
	 */
	public function testGetSoapClientNotExists()
	{
		$this->request->getSoapClient("\\SoapClientHujerMeteleskuBlesku");
	}
	
	public function testGetSoapClient()
	{
		$this->request->initialize(array("pgwInstructionsFile"=>__DIR__."/../Wsdl/pgw_wsdl_test.xml"));
		$this->assertInstanceOf("\SoapClient", $this->request->getSoapClient());
	}
	
	public function testGetOwnSoapClient()
	{
		$soap = $this->getMockBuilder("\SoapClient")
						->disableOriginalConstructor()
						->setMethods(array("queryOrderState"))
						->getMock();
		$this->request->setSoapClient($soap);
		$this->assertSame($soap, $this->request->getSoapClient());
	}
	
}
