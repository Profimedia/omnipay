<?php

namespace Omnipay\GPwebpay\Message;

use \Omnipay\Common\Message\AbstractResponse;
use \Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Paymuzo response
 *
 * @author Josef Macháček <josef.machacek@profimedia.cz>
 */
class Response extends AbstractResponse implements RedirectResponseInterface
{

	protected $liveEndpoint = 'https://3dsecure.gpwebpay.com/csob/order.do';
	protected $testEndpoint = 'https://test.3dsecure.gpwebpay.com/csob/order.do';

	public function isSuccessful()
	{
		if (isset($this->data['PRCODE']) && $this->data['PRCODE'] == 0 && isset($this->data['SRCODE']) && $this->data['SRCODE'] == 0)
		{
			return true;
		}
		return false;
	}

	public function isPending()
	{
		if (isset($this->data['PRCODE']) && $this->data['PRCODE'] == 28)
		{
			$pending = array(3001, 3002, 3004);
			if (isset($this->data['SRCODE']) && in_array($this->data['SRCODE'], $pending))
			{
				return true;
			}
		}
		return false;
	}

	public function isRedirect()
	{
		if (!isset($this->data['PRCODE']) && isset($this->data['OPERATION']) && $this->data['OPERATION'] == "CREATE_ORDER")
		{
			return true;
		}
		return false;
	}

	public function getRedirectMethod()
	{
		return "GET";
	}

	public function getRedirectUrl()
	{
		return $this->getEndpoint() . '?' . http_build_query($this->data);
	}

	public function getRedirectData()
	{
		return null;
	}

	public function getEndpoint()
	{
		return $this->request->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
	}

	public function getTransactionReference()
	{
		return isset($this->data['ORDERNUMBER']) ? $this->data['ORDERNUMBER'] : null;
	}

}