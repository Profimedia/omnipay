<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PayPal\Message;

/**
 * Description of GetTransactionDetailsRequest
 *
 * @author Josef Macháček <josef.machacek@profimedia.cz>
 */
class GetTransactionDetailsRequest extends AbstractRequest
{
	public function getData()
    {
        $data = $this->getBaseData('GetTransactionDetails');

        $this->validate('transactionReference');

        $data['TRANSACTIONID'] = $this->getTransactionReference();

        return $data;
    }
}
