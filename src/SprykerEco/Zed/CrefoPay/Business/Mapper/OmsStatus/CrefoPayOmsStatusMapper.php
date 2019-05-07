<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Mapper\OmsStatus;

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
        $statuses = $this->getNotificationTransactionToOmsStatusMapping();

        if (!isset($statuses[$apiStatus])) {
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
        $statuses = $this->getNotificationOrderToOmsStatusMapping();

        if (!isset($statuses[$apiStatus])) {
            return null;
        }

        return $statuses[$apiStatus];
    }

    /**
     * @return string[]
     */
    protected function getNotificationTransactionToOmsStatusMapping(): array
    {
        return $this->config->getNotificationTransactionToOmsStatusMapping();
    }

    /**
     * @return string[]
     */
    protected function getNotificationOrderToOmsStatusMapping(): array
    {
        return $this->config->getNotificationOrderToOmsStatusMapping();
    }
}
