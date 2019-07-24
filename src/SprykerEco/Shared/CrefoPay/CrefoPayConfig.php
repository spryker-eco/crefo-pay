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

    public const CREFO_PAY_PAYMENT_METHOD_BILL = 'crefoPayBill';
    public const CREFO_PAY_PAYMENT_METHOD_CASH_ON_DELIVERY = 'crefoPayCashOnDelivery';
    public const CREFO_PAY_PAYMENT_METHOD_DIRECT_DEBIT = 'crefoPayDirectDebit';
    public const CREFO_PAY_PAYMENT_METHOD_PAY_PAL = 'crefoPayPayPal';
    public const CREFO_PAY_PAYMENT_METHOD_PREPAID = 'crefoPayPrepaid';
    public const CREFO_PAY_PAYMENT_METHOD_SOFORT = 'crefoPaySofort';
    public const CREFO_PAY_PAYMENT_METHOD_CREDIT_CARD = 'crefoPayCreditCard';
    public const CREFO_PAY_PAYMENT_METHOD_CREDIT_CARD_3D = 'crefoPayCreditCard3D';

    protected const USER_RISK_CLASS = 1; //Possible values: 0 -> trusted user, 1 -> default risk user, 2 -> high risk user.
    protected const PRODUCT_TYPE_DEFAULT = 'DEFAULT';
    protected const PRODUCT_TYPE_SHIPPING_COSTS = 'SHIPPINGCOSTS';
    protected const PRODUCT_TYPE_COUPON = 'COUPON';

    protected const PRODUCT_RISK_CLASS = 1;
    protected const EXTERNAL_PAYMENT_METHOD_BILL = 'BILL';
    protected const EXTERNAL_PAYMENT_METHOD_CASH_ON_DELIVERY = 'COD';
    protected const EXTERNAL_PAYMENT_METHOD_DIRECT_DEBIT = 'DD';
    protected const EXTERNAL_PAYMENT_METHOD_PAYPAL = 'PAYPAL';
    protected const EXTERNAL_PAYMENT_METHOD_PREPAID = 'PREPAID';
    protected const EXTERNAL_PAYMENT_METHOD_SOFORT = 'SU';
    protected const EXTERNAL_PAYMENT_METHOD_CREDIT_CARD = 'CC';

    protected const EXTERNAL_PAYMENT_METHOD_CREDIT_CARD_3D = 'CC3D';

    /**
     * @return string
     */
    public function getProviderName(): string
    {
        return static::PROVIDER_NAME;
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
     * @return string
     */
    public function getProductTypeShippingCosts(): string
    {
        return static::PRODUCT_TYPE_SHIPPING_COSTS;
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
