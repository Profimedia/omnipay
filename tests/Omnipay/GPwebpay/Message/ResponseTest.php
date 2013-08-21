<?php

namespace Omnipay\GPwebpay\Message;

use Omnipay\TestCase;

/**
 * Description of Response
 *
 * @author Josef Macháček <josef.machacek@profimedia.cz>
 */
class ResponseTest extends TestCase
{
	private $request;
			
	public function setUp()
	{
		parent::setUp();
		$this->request = $this->getMockBuilder("\Omnipay\Common\Message\AbstractRequest")
								->disableOriginalConstructor()
								->getMock();
	}
	
	public function testRedirection()
	{
		$data = array("OPERATION"=>"CREATE_ORDER");
		$object = new Response($this->request, $data);
		$this->assertTrue($object->isRedirect());
		$this->assertFalse($object->isSuccessful());
		$this->assertFalse($object->isPending());
	}
	
	public function testSuccesful()
	{
		$data = array("PRCODE"=>0, "SRCODE"=>0);
		$object = new Response($this->request, $data);
		$this->assertFalse($object->isRedirect());
		$this->assertTrue($object->isSuccessful());
		$this->assertFalse($object->isPending());
	}
	
	public function testWrongRequest()
	{
		$data = array("PRCODE"=>28, "SRCODE"=>0);
		$object = new Response($this->request, $data);
		$this->assertFalse($object->isRedirect());
		$this->assertFalse($object->isSuccessful());
		$this->assertFalse($object->isPending());
	}
	
	public function testPending()
	{
		$data = array("PRCODE"=>28, "SRCODE"=>3001);
		$object = new Response($this->request, $data);
		$this->assertFalse($object->isRedirect());
		$this->assertFalse($object->isSuccessful());
		$this->assertTrue($object->isPending());
	}

	public function testGetRedirectMethod()
	{
		$data = array("PRCODE"=>28, "SRCODE"=>3001);
		$object = new Response($this->request, $data);
		$this->assertSame("GET", $object->getRedirectMethod());
	}
	
	public function testGetRedirectUrl()
	{
		$this->request->expects($this->once())
						->method("getTestMode")
						->will($this->returnValue(true));
		$data = array("PRCODE"=>28, "SRCODE"=>3001);
		$object = new Response($this->request, $data);
		$this->assertTrue((bool) strpos($object->getRedirectUrl(),"?PRCODE=28&SRCODE=3001"));
	}
	
	public function testGetRedirectData()
	{
		$data = array();
		$object = new Response($this->request, $data);
		$this->assertNull($object->getRedirectData());
	}
	
	public function testGetTransactionReference()
	{
		$data = array("ORDERNUMBER"=>28);
		$object = new Response($this->request, $data);
		$this->assertSame(28, $object->getTransactionReference());
	}
}