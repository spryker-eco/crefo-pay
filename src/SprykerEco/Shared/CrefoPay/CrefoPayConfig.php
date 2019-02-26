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

    public const INTEGRATION_TYPE = 'SecureFields'; //Possible values: API, SecureFields, HostedPageBefore, HostedPageAfter.
    public const CONTEXT = 'ONLINE'; //Possible values: ONLINE, OFFLINE.
    public const USER_TYPE = 'PRIVATE'; //Possible values: PRIVATE, BUSINESS.
    public const USER_RISK_CLASS = 0; //Possible values: 0 -> trusted user, 1 -> default risk user, 2 -> high risk user.
    public const PRODUCT_TYPE_DEFAULT = 'DEFAULT'; //DEFAULT, SHIPPINGCOSTS, COUPON
    public const PRODUCT_RISK_CLASS = 0;

    public const EXTERNAL_PAYMENT_METHOD_BILL = 'BILL';
    public const EXTERNAL_PAYMENT_METHOD_CASH_ON_DELIVERY = 'COD';
    public const EXTERNAL_PAYMENT_METHOD_DIRECT_DEBIT = 'DD';
    public const EXTERNAL_PAYMENT_METHOD_PAYPAL = 'PAYPAL';
    public const EXTERNAL_PAYMENT_METHOD_PREPAID = 'PREPAID';
    public const EXTERNAL_PAYMENT_METHOD_SOFORT = 'SU';
    public const EXTERNAL_PAYMENT_METHOD_CREDIT_CARD = 'CC';
    public const EXTERNAL_PAYMENT_METHOD_CREDIT_CARD_3D = 'CC3D';

    public const CREFO_PAY_PAYMENT_METHOD_BILL = 'crefoPayBill';
    public const CREFO_PAY_PAYMENT_METHOD_CASH_ON_DELIVERY = 'crefoPayCashOnDelivery';
    public const CREFO_PAY_PAYMENT_METHOD_DIRECT_DEBIT = 'crefoPayDirectDebit';
    public const CREFO_PAY_PAYMENT_METHOD_PAY_PAL = 'crefoPayPayPal';
    public const CREFO_PAY_PAYMENT_METHOD_PREPAID = 'crefoPayPrepaid';
    public const CREFO_PAY_PAYMENT_METHOD_SOFORT = 'crefoPaySofort';
    public const CREFO_PAY_PAYMENT_METHOD_CREDIT_CARD = 'crefoPayCreditCard';
    public const CREFO_PAY_PAYMENT_METHOD_CREDIT_CARD_3D = 'crefoPayCreditCard3D';

    public function getProviderName(): string
    {
        return static::PROVIDER_NAME;
    }

    /**
     * @return string
     */
    public function getIntegrationType(): string
    {
        return static::INTEGRATION_TYPE;
    }

    /**
     * @return string
     */
    public function getContext(): string
    {
        return static::CONTEXT;
    }

    /**
     * @return string
     */
    public function getUserType(): string
    {
        return static::USER_TYPE;
    }

    /**
     * @return int
     */
    public function getUserRiskClass(): int
    {
        return static::USER_RISK_CLASS;
    }

    /**
     * @return string
     */
    public function getProductTypeDefault(): string
    {
        return static::PRODUCT_TYPE_DEFAULT;
    }

    /**
     * @return int
     */
    public function getProductRiskClass(): int
    {
        return static::PRODUCT_RISK_CLASS;
    }

    /**
     * @return string
     */
    public function getExternalPaymentMethodBill(): string
    {
        return static::EXTERNAL_PAYMENT_METHOD_BILL;
    }

    /**
     * @return string
     */
    public function getExternalPaymentMethodCashOnDelivery(): string
    {
        return static::EXTERNAL_PAYMENT_METHOD_CASH_ON_DELIVERY;
    }

    /**
     * @return string
     */
    public function getExternalPaymentMethodDirectDebit(): string
    {
        return static::EXTERNAL_PAYMENT_METHOD_DIRECT_DEBIT;
    }

    /**
     * @return string
     */
    public function getExternalPaymentMethodPayPal(): string
    {
        return static::EXTERNAL_PAYMENT_METHOD_PAYPAL;
    }

    /**
     * @return string
     */
    public function getExternalPaymentMethodPrepaid(): string
    {
        return static::EXTERNAL_PAYMENT_METHOD_PREPAID;
    }

    /**
     * @return string
     */
    public function getExternalPaymentMethodSofort(): string
    {
        return static::EXTERNAL_PAYMENT_METHOD_SOFORT;
    }

    /**
     * @return string
     */
    public function getExternalPaymentMethodCreditCard(): string
    {
        return static::EXTERNAL_PAYMENT_METHOD_CREDIT_CARD;
    }

    /**
     * @return string
     */
    public function getExternalPaymentMethodCreditCard3D(): string
    {
        return static::EXTERNAL_PAYMENT_METHOD_CREDIT_CARD_3D;
    }

    /**
     * @return string
     */
    public function getCrefoPayPaymentMethodBill(): string
    {
        return static::CREFO_PAY_PAYMENT_METHOD_BILL;
    }

    /**
     * @return string
     */
    public function getCrefoPayPaymentMethodCashOnDelivery(): string
    {
        return static::CREFO_PAY_PAYMENT_METHOD_CASH_ON_DELIVERY;
    }

    /**
     * @return string
     */
    public function getCrefoPayPaymentMethodDirectDebit(): string
    {
        return static::CREFO_PAY_PAYMENT_METHOD_DIRECT_DEBIT;
    }

    /**
     * @return string
     */
    public function getCrefoPayPaymentMethodPayPal(): string
    {
        return static::CREFO_PAY_PAYMENT_METHOD_PAY_PAL;
    }

    /**
     * @return string
     */
    public function getCrefoPayPaymentMethodPrepaid(): string
    {
        return static::CREFO_PAY_PAYMENT_METHOD_PREPAID;
    }

    /**
     * @return string
     */
    public function getCrefoPayPaymentMethodSofort(): string
    {
        return static::CREFO_PAY_PAYMENT_METHOD_SOFORT;
    }

    /**
     * @return string
     */
    public function getCrefoPayPaymentMethodCreditCard(): string
    {
        return static::CREFO_PAY_PAYMENT_METHOD_CREDIT_CARD;
    }

    /**
     * @return string
     */
    public function getCrefoPayPaymentMethodCreditCard3D(): string
    {
        return static::CREFO_PAY_PAYMENT_METHOD_CREDIT_CARD_3D;
    }
}
