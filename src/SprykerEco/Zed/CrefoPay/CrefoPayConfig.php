<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\CrefoPay\CrefoPayConfig as SharedCrefoPayConfig;
use SprykerEco\Shared\CrefoPay\CrefoPayConstants;

/**
 * @method \SprykerEco\Shared\CrefoPay\CrefoPayConfig getSharedConfig()
 */
class CrefoPayConfig extends AbstractBundleConfig
{
    protected const NOTIFICATION_TRANSACTION_STATUS_ACKNOWLEDGE_PENDING = 'ACKNOWLEDGEPENDING';
    protected const NOTIFICATION_TRANSACTION_STATUS_FRAUD_PENDING = 'FRAUDPENDING';
    protected const NOTIFICATION_TRANSACTION_STATUS_FRAUD_CANCELLED = 'FRAUDCANCELLED';
    protected const NOTIFICATION_TRANSACTION_STATUS_CIA_PENDING = 'CIAPENDING';
    protected const NOTIFICATION_TRANSACTION_STATUS_MERCHANT_PENDING = 'MERCHANTPENDING';
    protected const NOTIFICATION_TRANSACTION_STATUS_CANCELLED = 'CANCELLED';
    protected const NOTIFICATION_TRANSACTION_STATUS_EXPIRED = 'EXPIRED';
    protected const NOTIFICATION_TRANSACTION_STATUS_IN_PROGRESS = 'INPROGRESS';
    protected const NOTIFICATION_TRANSACTION_STATUS_DONE = 'DONE';

    protected const NOTIFICATION_ORDER_STATUS_PAY_PENDING = 'PAYPENDING';
    protected const NOTIFICATION_ORDER_STATUS_PAID = 'PAID';
    protected const NOTIFICATION_ORDER_STATUS_CLEARED = 'CLEARED';
    protected const NOTIFICATION_ORDER_STATUS_PAYMENT_FAILED = 'PAYMENTFAILED';
    protected const NOTIFICATION_ORDER_STATUS_CHARGE_BACK = 'CHARGEBACK';
    protected const NOTIFICATION_ORDER_STATUS_IN_DUNNING = 'INDUNNING';
    protected const NOTIFICATION_ORDER_STATUS_IN_COLLECTION = 'IN_COLLECTION';

    protected const OMS_STATUS_NEW = 'new';
    protected const OMS_STATUS_RESERVED = 'reserved';
    protected const OMS_STATUS_AUTHORIZED = 'authorized';
    protected const OMS_STATUS_WAITING_FOR_CAPTURE = 'waiting for capture';
    protected const OMS_STATUS_CAPTURE_PENDING = 'capture pending';
    protected const OMS_STATUS_CAPTURED = 'captured';
    protected const OMS_STATUS_CANCELLATION_PENDING = 'cancellation pending';
    protected const OMS_STATUS_CANCELED = 'canceled';
    protected const OMS_STATUS_REFUNDED = 'refunded';
    protected const OMS_STATUS_MONEY_REDUCED = 'money reduced';
    protected const OMS_STATUS_EXPIRED = 'expired';
    protected const OMS_STATUS_DONE = 'done';

    protected const OMS_EVENT_CANCEL = 'cancel';
    protected const OMS_EVENT_NO_CANCELLATION = 'no cancellation';
    protected const OMS_EVENT_FINISH = 'finish';

    protected const CREFO_PAY_API_CAPTURE_ID_LENGTH = 30;

    protected const CREFO_PAY_AUTOMATIC_OMS_TRIGGER = 'CREFO_PAY_AUTOMATIC_OMS_TRIGGER';

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
    public function getRefundDescription(): string
    {
        return $this->get(CrefoPayConstants::REFUND_DESCRIPTION);
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
    public function getNotificationTransactionStatusAcknowledgePending(): string
    {
        return static::NOTIFICATION_TRANSACTION_STATUS_ACKNOWLEDGE_PENDING;
    }

    /**
     * @return string
     */
    public function getNotificationTransactionStatusMerchantPending(): string
    {
        return static::NOTIFICATION_TRANSACTION_STATUS_MERCHANT_PENDING;
    }

    /**
     * @return string
     */
    public function getNotificationTransactionStatusCancelled(): string
    {
        return static::NOTIFICATION_TRANSACTION_STATUS_CANCELLED;
    }

    /**
     * @return string
     */
    public function getNotificationTransactionStatusExpired(): string
    {
        return static::NOTIFICATION_TRANSACTION_STATUS_EXPIRED;
    }

    /**
     * @return string
     */
    public function getNotificationTransactionStatusDone(): string
    {
        return static::NOTIFICATION_TRANSACTION_STATUS_DONE;
    }

    /**
     * @return string
     */
    public function getNotificationOrderStatusPayPending(): string
    {
        return static::NOTIFICATION_ORDER_STATUS_PAY_PENDING;
    }

    /**
     * @return string
     */
    public function getNotificationOrderStatusPaid(): string
    {
        return static::NOTIFICATION_ORDER_STATUS_PAID;
    }

    /**
     * @return string
     */
    public function getNotificationOrderStatusChargeBack(): string
    {
        return static::NOTIFICATION_ORDER_STATUS_CHARGE_BACK;
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
    public function getOmsStatusCancellationPending(): string
    {
        return static::OMS_STATUS_CANCELLATION_PENDING;
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
    public function getOmsStatusMoneyReduced(): string
    {
        return static::OMS_STATUS_MONEY_REDUCED;
    }

    /**
     * @return string
     */
    public function getOmsStatusExpired(): string
    {
        return static::OMS_STATUS_EXPIRED;
    }

    /**
     * @return string
     */
    public function getOmsStatusDone(): string
    {
        return static::OMS_STATUS_DONE;
    }

    /**
     * @return string
     */
    public function getOmsEventCancel(): string
    {
        return static::OMS_EVENT_CANCEL;
    }

    /**
     * @return string
     */
    public function getOmsEventNoCancellation(): string
    {
        return static::OMS_EVENT_NO_CANCELLATION;
    }

    /**
     * @return string
     */
    public function getOmsEventFinish(): string
    {
        return static::OMS_EVENT_FINISH;
    }

    /**
     * @return int
     */
    public function getCrefoPayApiCaptureIdLength(): int
    {
        return static::CREFO_PAY_API_CAPTURE_ID_LENGTH;
    }

    /**
     * @return string
     */
    public function getCrefoPayAutomaticOmsTrigger(): string
    {
        return static::CREFO_PAY_AUTOMATIC_OMS_TRIGGER;
    }
}
