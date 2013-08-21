<?php

namespace Omnipay\PayPal\Message;

use Omnipay\TestCase;

/**
 * Description of GetTransactionDetailsRequestTest
 *
 * @author Josef Macháček <josef.machacek@profimedia.cz>
 */
class GetTransactionDetailsRequestTest extends TestCase
{
	private $transactionID;
	
	public function setUp()
	{
		parent::setUp();
		$paymentID = 12345;
		$this->transactionID = "abcdef1234";
		$this->request = new GetTransactionDetailsRequest($this->getHttpClient(), $this->getHttpRequest());
		$parameters = array("transactionReference" => $this->transactionID, "transactionId" => $paymentID);
		$this->request->initialize($parameters);
	}
	
	public function testGetData()
	{
		$data = $this->request->getData();
		$this->assertSame('GetTransactionDetails', $data['METHOD']);
		$this->assertSame('abcdef1234', $data['TRANSACTIONID']);
	}

}
