<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\CrefoPay\CrefoPayConstants;

/**
 * @method \SprykerEco\Shared\CrefoPay\CrefoPayConfig getSharedConfig()
 */
class CrefoPayConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const NOTIFICATION_TRANSACTION_STATUS_ACKNOWLEDGE_PENDING = 'ACKNOWLEDGEPENDING';

    /**
     * @var string
     */
    protected const NOTIFICATION_TRANSACTION_STATUS_FRAUD_PENDING = 'FRAUDPENDING';

    /**
     * @var string
     */
    protected const NOTIFICATION_TRANSACTION_STATUS_FRAUD_CANCELLED = 'FRAUDCANCELLED';

    /**
     * @var string
     */
    protected const NOTIFICATION_TRANSACTION_STATUS_CIA_PENDING = 'CIAPENDING';

    /**
     * @var string
     */
    protected const NOTIFICATION_TRANSACTION_STATUS_MERCHANT_PENDING = 'MERCHANTPENDING';

    /**
     * @var string
     */
    protected const NOTIFICATION_TRANSACTION_STATUS_CANCELLED = 'CANCELLED';

    /**
     * @var string
     */
    protected const NOTIFICATION_TRANSACTION_STATUS_EXPIRED = 'EXPIRED';

    /**
     * @var string
     */
    protected const NOTIFICATION_TRANSACTION_STATUS_IN_PROGRESS = 'INPROGRESS';

    /**
     * @var string
     */
    protected const NOTIFICATION_TRANSACTION_STATUS_DONE = 'DONE';

    /**
     * @var string
     */
    protected const NOTIFICATION_ORDER_STATUS_PAY_PENDING = 'PAYPENDING';

    /**
     * @var string
     */
    protected const NOTIFICATION_ORDER_STATUS_PAID = 'PAID';

    /**
     * @var string
     */
    protected const NOTIFICATION_ORDER_STATUS_CLEARED = 'CLEARED';

    /**
     * @var string
     */
    protected const NOTIFICATION_ORDER_STATUS_PAYMENT_FAILED = 'PAYMENTFAILED';

    /**
     * @var string
     */
    protected const NOTIFICATION_ORDER_STATUS_CHARGE_BACK = 'CHARGEBACK';

    /**
     * @var string
     */
    protected const NOTIFICATION_ORDER_STATUS_IN_DUNNING = 'INDUNNING';

    /**
     * @var string
     */
    protected const NOTIFICATION_ORDER_STATUS_IN_COLLECTION = 'IN_COLLECTION';

    /**
     * @var string
     */
    protected const OMS_STATUS_NEW = 'new';

    /**
     * @var string
     */
    protected const OMS_STATUS_RESERVED = 'reserved';

    /**
     * @var string
     */
    protected const OMS_STATUS_AUTHORIZED = 'authorized';

    /**
     * @var string
     */
    protected const OMS_STATUS_WAITING_FOR_CAPTURE = 'waiting for capture';

    /**
     * @var string
     */
    protected const OMS_STATUS_WAITING_FOR_CASH = 'waiting for cash';

    /**
     * @var string
     */
    protected const OMS_STATUS_CAPTURE_PENDING = 'capture pending';

    /**
     * @var string
     */
    protected const OMS_STATUS_CAPTURED = 'captured';

    /**
     * @var string
     */
    protected const OMS_STATUS_CANCELLATION_PENDING = 'cancellation pending';

    /**
     * @var string
     */
    protected const OMS_STATUS_CANCELED = 'canceled';

    /**
     * @var string
     */
    protected const OMS_STATUS_REFUNDED = 'refunded';

    /**
     * @var string
     */
    protected const OMS_STATUS_MONEY_REDUCED = 'money reduced';

    /**
     * @var string
     */
    protected const OMS_STATUS_EXPIRED = 'expired';

    /**
     * @var string
     */
    protected const OMS_STATUS_DONE = 'done';

    /**
     * @var string
     */
    protected const OMS_EVENT_CANCEL = 'cancel';

    /**
     * @var string
     */
    protected const OMS_EVENT_NO_CANCELLATION = 'no cancellation';

    /**
     * @var string
     */
    protected const OMS_EVENT_FINISH = 'finish';

    /**
     * @var int
     */
    protected const CREFO_PAY_API_CAPTURE_ID_LENGTH = 30;

    /**
     * @var string
     */
    protected const CREFO_PAY_AUTOMATIC_OMS_TRIGGER = 'CREFO_PAY_AUTOMATIC_OMS_TRIGGER';

    /**
     * @var array<string, string>
     */
    protected const CREFO_PAY_SALUTATION_MAP = [
        'Mr' => 'M',
        'Ms' => 'F',
        'Mrs' => 'F',
        'Dr' => 'M',
    ];

    /**
     * @api
     *
     * @return int
     */
    public function getMerchantId(): int
    {
        return $this->get(CrefoPayConstants::MERCHANT_ID);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getStoreId(): string
    {
        return $this->get(CrefoPayConstants::STORE_ID);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getRefundDescription(): string
    {
        return $this->get(CrefoPayConstants::REFUND_DESCRIPTION);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getProviderName(): string
    {
        return $this->getSharedConfig()->getProviderName();
    }

    /**
     * @api
     *
     * @return int
     */
    public function getUserRiskClass(): int
    {
        return $this->getSharedConfig()->getUserRiskClass();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getProductTypeDefault(): string
    {
        return $this->getSharedConfig()->getProductTypeDefault();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getProductTypeShippingCosts(): string
    {
        return $this->getSharedConfig()->getProductTypeShippingCosts();
    }

    /**
     * @api
     *
     * @return int
     */
    public function getProductRiskClass(): int
    {
        return $this->getSharedConfig()->getProductRiskClass();
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getInternalToExternalPaymentMethodNamesMapping(): array
    {
        return [
            $this->getSharedConfig()->getCrefoPayPaymentMethodBill() => $this->getSharedConfig()->getExternalPaymentMethodBill(),
            $this->getSharedConfig()->getCrefoPayPaymentMethodCashOnDelivery() => $this->getSharedConfig()->getExternalPaymentMethodCashOnDelivery(),
            $this->getSharedConfig()->getCrefoPayPaymentMethodDirectDebit() => $this->getSharedConfig()->getExternalPaymentMethodDirectDebit(),
            $this->getSharedConfig()->getCrefoPayPaymentMethodPayPal() => $this->getSharedConfig()->getExternalPaymentMethodPayPal(),
            $this->getSharedConfig()->getCrefoPayPaymentMethodPrepaid() => $this->getSharedConfig()->getExternalPaymentMethodPrepaid(),
            $this->getSharedConfig()->getCrefoPayPaymentMethodSofort() => $this->getSharedConfig()->getExternalPaymentMethodSofort(),
            $this->getSharedConfig()->getCrefoPayPaymentMethodCreditCard() => $this->getSharedConfig()->getExternalPaymentMethodCreditCard(),
            $this->getSharedConfig()->getCrefoPayPaymentMethodCreditCard3D() => $this->getSharedConfig()->getExternalPaymentMethodCreditCard3D(),
        ];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getNotificationTransactionStatusAcknowledgePending(): string
    {
        return static::NOTIFICATION_TRANSACTION_STATUS_ACKNOWLEDGE_PENDING;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getNotificationTransactionStatusMerchantPending(): string
    {
        return static::NOTIFICATION_TRANSACTION_STATUS_MERCHANT_PENDING;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getNotificationTransactionStatusCiaPending(): string
    {
        return static::NOTIFICATION_TRANSACTION_STATUS_CIA_PENDING;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getNotificationTransactionStatusCancelled(): string
    {
        return static::NOTIFICATION_TRANSACTION_STATUS_CANCELLED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getNotificationTransactionStatusExpired(): string
    {
        return static::NOTIFICATION_TRANSACTION_STATUS_EXPIRED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getNotificationTransactionStatusDone(): string
    {
        return static::NOTIFICATION_TRANSACTION_STATUS_DONE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getNotificationOrderStatusPayPending(): string
    {
        return static::NOTIFICATION_ORDER_STATUS_PAY_PENDING;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getNotificationOrderStatusPaid(): string
    {
        return static::NOTIFICATION_ORDER_STATUS_PAID;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getNotificationOrderStatusChargeBack(): string
    {
        return static::NOTIFICATION_ORDER_STATUS_CHARGE_BACK;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusNew(): string
    {
        return static::OMS_STATUS_NEW;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusReserved(): string
    {
        return static::OMS_STATUS_RESERVED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusAuthorized(): string
    {
        return static::OMS_STATUS_AUTHORIZED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusWaitingForCapture(): string
    {
        return static::OMS_STATUS_WAITING_FOR_CAPTURE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusWaitingForCash(): string
    {
        return static::OMS_STATUS_WAITING_FOR_CASH;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusCapturePending(): string
    {
        return static::OMS_STATUS_CAPTURE_PENDING;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusCaptured(): string
    {
        return static::OMS_STATUS_CAPTURED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusCancellationPending(): string
    {
        return static::OMS_STATUS_CANCELLATION_PENDING;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusCanceled(): string
    {
        return static::OMS_STATUS_CANCELED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusRefunded(): string
    {
        return static::OMS_STATUS_REFUNDED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusMoneyReduced(): string
    {
        return static::OMS_STATUS_MONEY_REDUCED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusExpired(): string
    {
        return static::OMS_STATUS_EXPIRED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusDone(): string
    {
        return static::OMS_STATUS_DONE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsEventCancel(): string
    {
        return static::OMS_EVENT_CANCEL;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsEventNoCancellation(): string
    {
        return static::OMS_EVENT_NO_CANCELLATION;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsEventFinish(): string
    {
        return static::OMS_EVENT_FINISH;
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getNotificationTransactionToOmsStatusMapping(): array
    {
        return [
            $this->getNotificationTransactionStatusAcknowledgePending() => $this->getOmsStatusAuthorized(),
            $this->getNotificationTransactionStatusMerchantPending() => $this->getOmsStatusWaitingForCapture(),
            $this->getNotificationTransactionStatusCiaPending() => $this->getOmsStatusWaitingForCash(),
            $this->getNotificationTransactionStatusCancelled() => $this->getOmsStatusCanceled(),
            $this->getNotificationTransactionStatusExpired() => $this->getOmsStatusExpired(),
            $this->getNotificationTransactionStatusDone() => $this->getOmsStatusDone(),
        ];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getNotificationOrderToOmsStatusMapping(): array
    {
        return [
            $this->getNotificationOrderStatusPayPending() => $this->getOmsStatusCapturePending(),
            $this->getNotificationOrderStatusPaid() => $this->getOmsStatusCaptured(),
            $this->getNotificationOrderStatusChargeBack() => $this->getOmsStatusRefunded(),
        ];
    }

    /**
     * @api
     *
     * @return int
     */
    public function getCrefoPayApiCaptureIdLength(): int
    {
        return static::CREFO_PAY_API_CAPTURE_ID_LENGTH;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCrefoPayAutomaticOmsTrigger(): string
    {
        return static::CREFO_PAY_AUTOMATIC_OMS_TRIGGER;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function getIsBusinessToBusiness(): bool
    {
        return $this->get(CrefoPayConstants::IS_BUSINESS_TO_BUSINESS);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function getCaptureExpensesSeparately(): bool
    {
        return $this->get(CrefoPayConstants::CAPTURE_EXPENSES_SEPARATELY);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function getRefundExpensesWithLastItem(): bool
    {
        return $this->get(CrefoPayConstants::REFUND_EXPENSES_WITH_LAST_ITEM);
    }

    /**
     * @api
     *
     * @return array<string, string>
     */
    public function getCrefoPaySalutationMap(): array
    {
        return static::CREFO_PAY_SALUTATION_MAP;
    }
}
