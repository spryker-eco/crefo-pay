<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Shared\CrefoPay;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class CrefoPayConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @var string
     */
    public const PROVIDER_NAME = 'crefoPay';

    /**
     * @api
     *
     * @var string
     */
    public const CREFO_PAY_PAYMENT_METHOD_BILL = 'crefoPayBill';

    /**
     * @api
     *
     * @var string
     */
    public const CREFO_PAY_PAYMENT_METHOD_CASH_ON_DELIVERY = 'crefoPayCashOnDelivery';

    /**
     * @api
     *
     * @var string
     */
    public const CREFO_PAY_PAYMENT_METHOD_DIRECT_DEBIT = 'crefoPayDirectDebit';

    /**
     * @api
     *
     * @var string
     */
    public const CREFO_PAY_PAYMENT_METHOD_PAY_PAL = 'crefoPayPayPal';

    /**
     * @api
     *
     * @var string
     */
    public const CREFO_PAY_PAYMENT_METHOD_PREPAID = 'crefoPayPrepaid';

    /**
     * @api
     *
     * @var string
     */
    public const CREFO_PAY_PAYMENT_METHOD_SOFORT = 'crefoPaySofort';

    /**
     * @api
     *
     * @var string
     */
    public const CREFO_PAY_PAYMENT_METHOD_CREDIT_CARD = 'crefoPayCreditCard';

    /**
     * @api
     *
     * @var string
     */
    public const CREFO_PAY_PAYMENT_METHOD_CREDIT_CARD_3D = 'crefoPayCreditCard3D';

    /**
     * Possible values:
     * 0 -> trusted user
     * 1 -> default risk user
     * 2 -> high risk user
     *
     * @var int
     */
    protected const USER_RISK_CLASS = 1;

    /**
     * @var string
     */
    protected const PRODUCT_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @var string
     */
    protected const PRODUCT_TYPE_SHIPPING_COSTS = 'SHIPPINGCOSTS';

    /**
     * @var string
     */
    protected const PRODUCT_TYPE_COUPON = 'COUPON';

    /**
     * @var int
     */
    protected const PRODUCT_RISK_CLASS = 1;

    /**
     * @var string
     */
    protected const EXTERNAL_PAYMENT_METHOD_BILL = 'BILL';

    /**
     * @var string
     */
    protected const EXTERNAL_PAYMENT_METHOD_CASH_ON_DELIVERY = 'COD';

    /**
     * @var string
     */
    protected const EXTERNAL_PAYMENT_METHOD_DIRECT_DEBIT = 'DD';

    /**
     * @var string
     */
    protected const EXTERNAL_PAYMENT_METHOD_PAYPAL = 'PAYPAL';

    /**
     * @var string
     */
    protected const EXTERNAL_PAYMENT_METHOD_PREPAID = 'PREPAID';

    /**
     * @var string
     */
    protected const EXTERNAL_PAYMENT_METHOD_SOFORT = 'SU';

    /**
     * @var string
     */
    protected const EXTERNAL_PAYMENT_METHOD_CREDIT_CARD = 'CC';

    /**
     * @var string
     */
    protected const EXTERNAL_PAYMENT_METHOD_CREDIT_CARD_3D = 'CC3D';

    /**
     * @api
     *
     * @return string
     */
    public function getProviderName(): string
    {
        return static::PROVIDER_NAME;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getUserRiskClass(): int
    {
        return static::USER_RISK_CLASS;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getProductTypeDefault(): string
    {
        return static::PRODUCT_TYPE_DEFAULT;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getProductTypeShippingCosts(): string
    {
        return static::PRODUCT_TYPE_SHIPPING_COSTS;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getProductRiskClass(): int
    {
        return static::PRODUCT_RISK_CLASS;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getExternalPaymentMethodBill(): string
    {
        return static::EXTERNAL_PAYMENT_METHOD_BILL;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getExternalPaymentMethodCashOnDelivery(): string
    {
        return static::EXTERNAL_PAYMENT_METHOD_CASH_ON_DELIVERY;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getExternalPaymentMethodDirectDebit(): string
    {
        return static::EXTERNAL_PAYMENT_METHOD_DIRECT_DEBIT;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getExternalPaymentMethodPayPal(): string
    {
        return static::EXTERNAL_PAYMENT_METHOD_PAYPAL;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getExternalPaymentMethodPrepaid(): string
    {
        return static::EXTERNAL_PAYMENT_METHOD_PREPAID;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getExternalPaymentMethodSofort(): string
    {
        return static::EXTERNAL_PAYMENT_METHOD_SOFORT;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getExternalPaymentMethodCreditCard(): string
    {
        return static::EXTERNAL_PAYMENT_METHOD_CREDIT_CARD;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getExternalPaymentMethodCreditCard3D(): string
    {
        return static::EXTERNAL_PAYMENT_METHOD_CREDIT_CARD_3D;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCrefoPayPaymentMethodBill(): string
    {
        return static::CREFO_PAY_PAYMENT_METHOD_BILL;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCrefoPayPaymentMethodCashOnDelivery(): string
    {
        return static::CREFO_PAY_PAYMENT_METHOD_CASH_ON_DELIVERY;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCrefoPayPaymentMethodDirectDebit(): string
    {
        return static::CREFO_PAY_PAYMENT_METHOD_DIRECT_DEBIT;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCrefoPayPaymentMethodPayPal(): string
    {
        return static::CREFO_PAY_PAYMENT_METHOD_PAY_PAL;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCrefoPayPaymentMethodPrepaid(): string
    {
        return static::CREFO_PAY_PAYMENT_METHOD_PREPAID;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCrefoPayPaymentMethodSofort(): string
    {
        return static::CREFO_PAY_PAYMENT_METHOD_SOFORT;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCrefoPayPaymentMethodCreditCard(): string
    {
        return static::CREFO_PAY_PAYMENT_METHOD_CREDIT_CARD;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCrefoPayPaymentMethodCreditCard3D(): string
    {
        return static::CREFO_PAY_PAYMENT_METHOD_CREDIT_CARD_3D;
    }
}
