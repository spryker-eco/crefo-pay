<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Processor\Mapper;

interface CrefoPayNotificationStatusMapperInterface
{
    /**
     * @param string $notificationStatus
     *
     * @return string|null
     */
    public function mapNotificationTransactionStatusToOmsStatus(string $notificationStatus): ?string;

    /**
     * @param string $notificationStatus
     *
     * @return string|null
     */
    public function mapNotificationOrderStatusToOmsStatus(string $notificationStatus): ?string;
}
