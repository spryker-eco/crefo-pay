<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Mapper;

use SprykerEco\Zed\CrefoPay\CrefoPayConfig;

class CrefoPayOmsStatusMapper implements CrefoPayOmsStatusMapperInterface
{
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
            $this->config->getNotificationTransactionStatusAcknowledgePending() => $this->config->getOmsStatusAuthorized(),
            $this->config->getNotificationTransactionStatusMerchantPending() => $this->config->getOmsStatusWaitingForCapture(),
            $this->config->getNotificationTransactionStatusCancelled() => $this->config->getOmsStatusCanceled(),
            $this->config->getNotificationTransactionStatusExpired() => $this->config->getOmsStatusExpired(),
            $this->config->getNotificationTransactionStatusDone() => $this->config->getOmsStatusDone(),
        ];
    }

    /**
     * @return array
     */
    protected function getMappedOrderStatuses(): array
    {
        return [
            $this->config->getNotificationOrderStatusPayPending() => $this->config->getOmsStatusCapturePending(),
            $this->config->getNotificationOrderStatusPaid() => $this->config->getOmsStatusCaptured(),
            $this->config->getNotificationOrderStatusChargeBack() => $this->config->getOmsStatusRefunded(),
        ];
    }
}
