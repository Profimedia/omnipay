<?php

namespace Omnipay\GPwebpay\Message;

use \Omnipay\Common\Message\AbstractRequest;
use \Omnipay\Common\Exception\RuntimeException;

/**
 * Create transation request
 *
 * @author Josef Macháček <josef.machacek@profimedia.cz>
 */
class Request extends AbstractRequest
{

	private $supportedCurrencies = array(
		"CZK" => 203,
		"USD" => 840,
		"EUR" => 978,
		"GBP" => 826
	);

	public function getData()
	{
		$this->validate('amount', 'currency', 'returnUrl','transactionId','returnUrl');
		$currency = $this->getCurrency();
		if(!array_key_exists($currency, $this->supportedCurrencies))
		{
			throw new RuntimeException("Currency {$currency} is not supported by gpwebpay gateway");
		}
		$parameters['MERCHANTNUMBER'] = $this->getParameter('merchantID');
		$parameters['OPERATION'] = $this->getParameter('operation');
		$parameters['ORDERNUMBER'] = $this->getTransactionId();
		$parameters['AMOUNT'] = $this->getAmount();
		$parameters['CURRENCY'] = $this->supportedCurrencies[$currency];
		$parameters['DEPOSITFLAG'] = $this->getParameter('depositflag');
		$parameters['MERORDERNUM'] = $this->getTransactionId();
		$parameters['URL'] = $this->getReturnUrl();

		$dataToSign = implode("|", $parameters);
		$privateKey = $this->getPrivateKeyResource();
		$digest = "";
		openssl_sign($dataToSign, $digest, $privateKey);
		$parameters['DIGEST'] = base64_encode($digest);
		openssl_free_key($privateKey);
		
		return $parameters;
	}

	public function send()
	{
		return $this->response = new Response($this, $this->getData());
	}

	protected function getPrivateKeyResource()
	{
		$privateKey = null;
		$privateKeyPath = $this->getParameter("privateKeyPemPath");
		if (file_exists($privateKeyPath) && is_readable($privateKeyPath))
		{
			$key = file_get_contents($privateKeyPath);
			$privateKey = openssl_get_privatekey($key, $this->getParameter("privateKeyPassword"));
		}
		if (!is_resource($privateKey))
		{
			$message = "Cannot load private key";
			throw new RuntimeException($message);
		}
		return $privateKey;
	}
	
	protected function getPublicKeyResource()
	{
		$publicKey = null;
		$publicKeyPath = $this->getParameter("bankPublicKeyPemPath");
		if (file_exists($publicKeyPath) && is_readable($publicKeyPath))
		{
			$key = file_get_contents($publicKeyPath);
			$publicKey = openssl_get_publickey($key);
		}

		if (!is_resource($publicKey))
		{
			$message = "Cannot load gpwebpay public key";
			throw new RuntimeException($message);
		}
		return $publicKey;
	}
	
	public function setAmount($amount)
	{
		return $this->setParameter('amount', $amount);
	}

	public function getAmount()
	{
		$amount = \number_format($this->getParameter('amount'), 2, '.', '') * 100;
		return (int) $amount;
	}

	public function setCurrency($currency)
	{
		return $this->setParameter('currency', $currency);
	}

	public function getCurrency()
	{
		return $this->getParameter('currency');
	}

	public function getDepositflag()
	{
		return $this->getParameter('depositflag');
	}
	
	public function setDepositflag($depositflag)
	{
		return $this->setParameter('depositflag', $depositflag);
	}
	
	public function setReturnUrl($returnUrl)
	{
		return $this->setParameter('returnUrl', $returnUrl);
	}

	public function getReturnUrl()
	{
		return $this->getParameter('returnUrl');
	}

	public function setMerchantID($merchantID)
	{
		return $this->setParameter('merchantID', $merchantID);
	}

	public function getMerchantID()
	{
		return $this->getParameter('merchantID');
	}
	
	public function getOperation()
	{
		return $this->getParameter('operation');
	}
	
	public function setOperation($operation)
	{
		return $this->setParameter('operation', $operation);
	}
	
	public function getTransactionId()
    {
        return $this->getParameter('transactionId');
    }

    public function setTransactionId($value)
    {
        return $this->setParameter('transactionId', $value);
    }
	
	public function setPrivateKeyPemPath($path)
	{
		return $this->setParameter('privateKeyPemPath', $path);
	}
	
	public function getPrivateKeyPemPath()
	{
		return $this->getParameter('privateKeyPemPath');
	}

	public function setPrivateKeyPassword($password)
	{
		return $this->setParameter('privateKeyPassword', $password);
	}
	
	public function getPrivateKeyPassword()
	{
		return $this->getParameter('privateKeyPassword');
	}
	
	public function setBankPublicKeyPemPath($path)
	{
		return $this->setParameter('bankPublicKeyPemPath', $path);
	}
	
	public function getBankPublicKeyPemPath()
	{
		return $this->getParameter('bankPublicKeyPemPath');
	}
	
	public function setPgwInstructionsFile($path)
	{
		return $this->setParameter('pgwInstructionsFile', $path);
	}
	
	public function getPgwInstructionsFile()
	{
		return $this->getParameter('pgwInstructionsFile');
	}
}
