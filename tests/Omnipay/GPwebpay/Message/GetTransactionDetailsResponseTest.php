<?php

namespace Omnipay\GPwebpay\Message;

use Omnipay\TestCase;

/**
 * Description of GetTransactionDetailsResponse
 *
 * @author Josef Macháček <josef.machacek@profimedia.cz>
 */
class GetTransactionDetailsResponseTest extends TestCase
{
	private $request;
			
	public function setUp()
	{
		parent::setUp();
		$this->request = $this->getMockBuilder("\Omnipay\Common\Message\RequestInterface")->getMock();
	}
	
	public function testSuccessfulTransaction()
	{
		$data = array("state"=>7);
		$object = new GetTransactionDetailsResponse($this->request, $data);
		$this->assertTrue($object->isSuccessful());
		$this->assertFalse($object->isPending());
		$this->assertFalse($object->isRedirect());
	}
	
	public function testPendingTransaction()
	{
		$data = array("state"=>1);
		$object = new GetTransactionDetailsResponse($this->request, $data);
		$this->assertFalse($object->isSuccessful());
		$this->assertTrue($object->isPending());
	}
	
}
