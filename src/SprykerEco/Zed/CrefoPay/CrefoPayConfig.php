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
    protected const OMS_STATUS_NEW = 'new';
    protected const OMS_STATUS_RESERVED = 'reserved';
    protected const OMS_STATUS_AUTHORIZED = 'authorized';
    protected const OMS_STATUS_WAITING_FOR_CAPTURE = 'waiting for capture';
    protected const OMS_STATUS_CAPTURE_PENDING = 'capture pending';
    protected const OMS_STATUS_CAPTURED = 'captured';
    protected const OMS_STATUS_CANCELED = 'canceled';
    protected const OMS_STATUS_REFUNDED = 'refunded';
    protected const OMS_STATUS_FINISHED = 'finished';

    protected const CREFO_PAY_API_CAPTURE_ID_LENGTH = 30;

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

    /**
     * @return string
     */
    public function getOmsStatusNew(): string
    {
        return static::OMS_STATUS_NEW;
    }

    /**
     * @return string
     */
    public function getOmsStatusReserved(): string
    {
        return static::OMS_STATUS_RESERVED;
    }

    /**
     * @return string
     */
    public function getOmsStatusAuthorized(): string
    {
        return static::OMS_STATUS_AUTHORIZED;
    }

    /**
     * @return string
     */
    public function getOmsStatusWaitingForCapture(): string
    {
        return static::OMS_STATUS_WAITING_FOR_CAPTURE;
    }

    /**
     * @return string
     */
    public function getOmsStatusCapturePending(): string
    {
        return static::OMS_STATUS_CAPTURE_PENDING;
    }

    /**
     * @return string
     */
    public function getOmsStatusCaptured(): string
    {
        return static::OMS_STATUS_CAPTURED;
    }

    /**
     * @return string
     */
    public function getOmsStatusCanceled(): string
    {
        return static::OMS_STATUS_CANCELED;
    }

    /**
     * @return string
     */
    public function getOmsStatusRefunded(): string
    {
        return static::OMS_STATUS_REFUNDED;
    }

    /**
     * @return string
     */
    public function getOmsStatusFinished(): string
    {
        return static::OMS_STATUS_FINISHED;
    }

    /**
     * @return int
     */
    public function getCrefoPayApiCaptureIdLength(): int
    {
        return static::CREFO_PAY_API_CAPTURE_ID_LENGTH;
    }
}
