<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Mapper;

use SprykerEco\Zed\CrefoPay\CrefoPayConfig;

class CrefoPayOmsStatusMapper implements CrefoPayOmsStatusMapperInterface
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

    /**
     * @var \SprykerEco\Zed\CrefoPay\CrefoPayConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\CrefoPay\CrefoPayConfig $config
     */
    public function __construct(CrefoPayConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $apiStatus
     *
     * @return string|null
     */
    public function mapNotificationTransactionStatusToOmsStatus(string $apiStatus): ?string
    {
        $statuses = $this->getMappedTransactionStatuses();

        if (!array_key_exists($apiStatus, $statuses)) {
            return null;
        }

        return $statuses[$apiStatus];
    }

    /**
     * @param string $apiStatus
     *
     * @return string|null
     */
    public function mapNotificationOrderStatusToOmsStatus(string $apiStatus): ?string
    {
        $statuses = $this->getMappedOrderStatuses();

        if (!array_key_exists($apiStatus, $statuses)) {
            return null;
        }

        return $statuses[$apiStatus];
    }

    /**
     * @return string[]
     */
    protected function getMappedTransactionStatuses(): array
    {
        return [
            static::NOTIFICATION_TRANSACTION_STATUS_ACKNOWLEDGE_PENDING => $this->config->getOmsStatusAuthorized(),
            static::NOTIFICATION_TRANSACTION_STATUS_FRAUD_PENDING => $this->config->getOmsStatusNew(),
            static::NOTIFICATION_TRANSACTION_STATUS_FRAUD_CANCELLED => $this->config->getOmsStatusNew(),
            static::NOTIFICATION_TRANSACTION_STATUS_CIA_PENDING => $this->config->getOmsStatusNew(),
            static::NOTIFICATION_TRANSACTION_STATUS_MERCHANT_PENDING => $this->config->getOmsStatusWaitingForCapture(),
            static::NOTIFICATION_TRANSACTION_STATUS_CANCELLED => $this->config->getOmsStatusCanceled(),
            static::NOTIFICATION_TRANSACTION_STATUS_EXPIRED => $this->config->getOmsStatusNew(),
            static::NOTIFICATION_TRANSACTION_STATUS_IN_PROGRESS => $this->config->getOmsStatusNew(),
            static::NOTIFICATION_TRANSACTION_STATUS_DONE => $this->config->getOmsStatusNew(),
        ];
    }

    /**
     * @return array
     */
    protected function getMappedOrderStatuses(): array
    {
        return [
            static::NOTIFICATION_ORDER_STATUS_PAY_PENDING => $this->config->getOmsStatusCapturePending(),
            static::NOTIFICATION_ORDER_STATUS_PAID => $this->config->getOmsStatusCaptured(),
            static::NOTIFICATION_ORDER_STATUS_CLEARED => $this->config->getOmsStatusNew(),
            static::NOTIFICATION_ORDER_STATUS_PAYMENT_FAILED => $this->config->getOmsStatusNew(),
            static::NOTIFICATION_ORDER_STATUS_CHARGE_BACK => $this->config->getOmsStatusNew(),
            static::NOTIFICATION_ORDER_STATUS_IN_DUNNING => $this->config->getOmsStatusNew(),
            static::NOTIFICATION_ORDER_STATUS_IN_COLLECTION => $this->config->getOmsStatusNew(),
        ];
    }
}
