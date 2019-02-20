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
    public const PRODUCT_TYPE_DEFAULT = 'DEFAULT'; //DEFAULT, SHIPPINGCOSTS, COUPON
    public const PRODUCT_TYPE_SHIPPING = 'SHIPPINGCOSTS'; //DEFAULT, SHIPPINGCOSTS, COUPON
    public const PRODUCT_RISK_CLASS = 0;

    public const PAYMENT_METHOD_BILL = 'BILL';
    public const PAYMENT_METHOD_CASH_ON_DELIVERY = 'COD';
    public const PAYMENT_METHOD_DIRECT_DEBIT = 'DD';
    public const PAYMENT_METHOD_PAYPAL = 'PAYPAL';
    public const PAYMENT_METHOD_PREPAID = 'PREPAID';
    public const PAYMENT_METHOD_SOFORT = 'SU';
    public const PAYMENT_METHOD_CREDIT_CARD = 'CC';
    public const PAYMENT_METHOD_CREDIT_CARD_3D = 'CC3D';

    public const CREFO_PAY_BILL = 'crefoPayBill';
    public const CREFO_PAY_CASH_ON_DELIVERY = 'crefoPayCashOnDelivery';
    public const CREFO_PAY_DIRECT_DEBIT = 'crefoPayDirectDebit';
    public const CREFO_PAY_PAY_PAL = 'crefoPayPayPal';
    public const CREFO_PAY_PREPAID = 'crefoPayPrepaid';
    public const CREFO_PAY_SOFORT = 'crefoPaySofort';
    public const CREFO_PAY_CREDIT_CARD = 'crefoPayCreditCard';
    public const CREFO_PAY_CREDIT_CARD_3D = 'crefoPayCreditCard3D';
}
