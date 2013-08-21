<?php

namespace Omnipay\GPwebpay;

use Omnipay\Common\AbstractGateway;

/**
 * Gateway for GPwebpay (paymuzo)
 *
 * @author Josef Macháček <josef.machacek@profimedia.cz>
 */
class Gateway extends AbstractGateway
{

	public function __construct($httpClient = null, $httpRequest = null)
	{
		if (!function_exists("openssl_sign"))
		{
			throw new Omnipay\Common\Exception\RuntimeException("openssl extension not found");
		}
		parent::__construct($httpClient, $httpRequest);
	}

	public function getName()
	{
		return 'GPwebpay';
	}

	public function getDefaultParameters()
	{
		$parameters = array(
			'merchantID' => "",
			'operation' => "CREATE_ORDER",
			'ordernumber' => "",
			'amount' => "",
			'currency' => "",
			'depositflag' => 1,
			'merordernum' => "",
			'returnUrl' => "",
			'bankPublicKeyPemPath' => "",
			'privateKeyPemPath' => "",
			'privateKeyPassword' => "",
			'pgwInstructionsFile' => "",
			'testMode' => false
		);
		return $parameters;
	}

	public function purchase(array $parameters = array())
	{
		return $this->createRequest('\Omnipay\GPwebpay\Message\Request', $parameters);
	}

	public function completePurchase(array $parameters = array())
	{
		return $this->createRequest('\Omnipay\GPwebpay\Message\CompleteRequest', $parameters);
	}

	public function getTransactionDetails(array $parameters = array())
	{
		return $this->createRequest('\Omnipay\GPwebpay\Message\GetTransactionDetailsRequest', $parameters);
	}

	public function setMerchantID($merchantID)
	{
		return $this->setParameter('merchantID', $merchantID);
	}

	public function getMerchantID()
	{
		return $this->getParameter('merchantID');
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
