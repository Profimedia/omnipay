<?php

namespace Omnipay\GPwebpay\Message;

/**
 * Request for transaction payment status
 *
 * @author Josef Macháček <josef.machacek@profimedia.cz>
 */
class GetTransactionDetailsRequest extends Request
{

	private $soapClient;

	public function getData()
	{
		$this->validate('transactionId');

		$transactionID = $this->getParameter('transactionId');
		$merchant = $this->getParameter('merchantID');

		$dataToSign = "{$merchant}|{$transactionID}";
		$privateKey = $this->getPrivateKeyResource();
		$digest = "";
		openssl_sign($dataToSign, $digest, $privateKey);
		$digest = base64_encode($digest);
		openssl_free_key($privateKey);

		return $digest;
	}

	public function send()
	{
		$soapClient = $this->getSoapClient();
		$transactionID = $this->getParameter('transactionId');
		$merchant = $this->getParameter('merchantID');

		$details = $soapClient->queryOrderState($merchant, $transactionID, $this->getData());
		return $this->response = new GetTransactionDetailsResponse($this, (array) $details);
	}

	public function setSoapClient(\SoapClient $soapClient)
	{
		$this->soapClient = $soapClient;
		return $this;
	}

	public function getSoapClient($soapClientClassName = "\\SoapClient")
	{
		if (!isset($this->soapClient))
		{
			if (!class_exists($soapClientClassName))
			{
				throw new \Omnipay\Common\Exception\RuntimeException("class {$soapClientClassName} needed");
			}
			$this->soapClient = new $soapClientClassName($this->getPgwInstructionsFile());
		}
		return $this->soapClient;
	}

}
