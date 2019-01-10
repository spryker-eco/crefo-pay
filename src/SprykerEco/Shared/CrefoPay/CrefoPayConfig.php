<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Shared\CrefoPay;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class CrefoPayConfig extends AbstractBundleConfig
{
    public const PROVIDER_NAME = 'crefoPay';

    public const INTEGRATION_TYPE = 'API'; //Possible values: API, SecureFields, HostedPageBefore, HostedPageAfter.
    public const CONTEXT = 'ONLINE'; //Possible values: ONLINE, OFFLINE.
    public const USER_TYPE = 'PRIVATE'; //Possible values: PRIVATE, BUSINESS.
    public const USER_RISK_CLASS = 0; //Possible values: 0 -> trusted user, 1 -> default risk user, 2 -> high risk user.

    public const PAYMENT_METHOD_BILL = 'BILL';
    public const PAYMENT_METHOD_CASH_ON_DELIVERY = 'COD';
    public const PAYMENT_METHOD_DIRECT_DEBIT = 'DD';
    public const PAYMENT_METHOD_PAYPAL = 'PAYPAL';
    public const PAYMENT_METHOD_PREPAID = 'PREPAID';
    public const PAYMENT_METHOD_SOFORT = 'SU';
    public const PAYMENT_METHOD_CREDIT_CARD = 'CC';
}
