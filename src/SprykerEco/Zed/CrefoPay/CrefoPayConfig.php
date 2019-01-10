<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\CrefoPay\CrefoPayConfig as SharedCrefoPayConfig;
use SprykerEco\Shared\CrefoPay\CrefoPayConstants;

class CrefoPayConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getMerchantId(): string
    {
        return $this->get(CrefoPayConstants::MERCHANT_ID);
    }

    /**
     * @return string
     */
    public function getStoreId(): string
    {
        return $this->get(CrefoPayConstants::STORE_ID);
    }

    /**
     * @return string
     */
    public function getAutoCapture(): string
    {
        return $this->get(CrefoPayConstants::AUTO_CAPTURE);
    }

    /**
     * @return string
     */
    public function getIntegrationType(): string
    {
        return SharedCrefoPayConfig::INTEGRATION_TYPE;
    }

    /**
     * @return string
     */
    public function getContext(): string
    {
        return SharedCrefoPayConfig::CONTEXT;
    }

    /**
     * @return string
     */
    public function getUserType(): string
    {
        return SharedCrefoPayConfig::USER_TYPE;
    }

    /**
     * @return int
     */
    public function getUserRiskClass(): int
    {
        return SharedCrefoPayConfig::USER_RISK_CLASS;
    }

    /**
     * @return string[]
     */
    public function getAvailablePaymentMethodsMapping(): array
    {
        return [
            SharedCrefoPayConfig::PAYMENT_METHOD_BILL => SharedCrefoPayConfig::CREFO_PAY_BILL,
            SharedCrefoPayConfig::PAYMENT_METHOD_CASH_ON_DELIVERY => SharedCrefoPayConfig::CREFO_PAY_CASH_ON_DELIVERY,
            SharedCrefoPayConfig::PAYMENT_METHOD_DIRECT_DEBIT => SharedCrefoPayConfig::CREFO_PAY_DIRECT_DEBIT,
            SharedCrefoPayConfig::PAYMENT_METHOD_PAYPAL => SharedCrefoPayConfig::CREFO_PAY_PAY_PAL,
            SharedCrefoPayConfig::PAYMENT_METHOD_PREPAID => SharedCrefoPayConfig::CREFO_PAY_PREPAID,
            SharedCrefoPayConfig::PAYMENT_METHOD_SOFORT => SharedCrefoPayConfig::CREFO_PAY_SOFORT,
        ];
    }
}
