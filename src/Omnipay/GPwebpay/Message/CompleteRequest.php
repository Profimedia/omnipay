<?php

namespace Omnipay\GPwebpay\Message;

use \Omnipay\Common\Exception\RuntimeException;

/**
 * Finish transaction request
 *
 * @author Josef Macháček <josef.machacek@profimedia.cz>
 */
class CompleteRequest extends Request
{

	public function getData()
	{
		$digestParam = $this->httpRequest->get('DIGEST');
		if ($digestParam == false)
		{
			$message = "Digest missing in response";
			throw new RuntimeException($message);
		}

		$needed = array("OPERATION" => "", "ORDERNUMBER" => "", "MERORDERNUM" => "", "PRCODE" => "", "SRCODE" => "", "RESULTTEXT" => "");
		foreach ($needed as $key => $item)
		{
			$getParam = $this->httpRequest->get($key);
			if ($getParam === false)
			{
				$message = "Bad response format";
				throw new RuntimeException($message);
			}
			$needed[$key] = $getParam;
		}

		$toCheck = implode("|", $needed);
		$digest = base64_decode($digestParam);

		$publicKey = $this->getPublicKeyResource();

		$verified = (bool) openssl_verify($toCheck, $digest, $publicKey);
		openssl_free_key($publicKey);

		if ($verified === false)
		{
			$message = "Signature verifying unsuccessful";
			throw new RuntimeException($message);
		}

		return $needed;
	}

}
