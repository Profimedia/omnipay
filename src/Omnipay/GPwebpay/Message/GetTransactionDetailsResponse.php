<?php

namespace Omnipay\GPwebpay\Message;

/**
 * Response with transaction status
 *
 * @author Josef Macháček <josef.machacek@profimedia.cz>
 */
class GetTransactionDetailsResponse extends Response
{

	public function isSuccessful()
	{
		if (isset($this->data['state']) && in_array($this->data['state'], array(7, 8, 9, 11, 12)))
		{
			return true;
		}
		return false;
	}

	public function isPending()
	{
		if (isset($this->data['state']) && $this->data['state'] < 5)
		{
			return true;
		}
		return false;
	}

	public function isRedirect()
	{
		return false;
	}

}
