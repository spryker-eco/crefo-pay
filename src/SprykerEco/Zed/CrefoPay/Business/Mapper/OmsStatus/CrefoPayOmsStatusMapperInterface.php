<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Mapper\OmsStatus;

interface CrefoPayOmsStatusMapperInterface
{
    /**
     * @param string $apiStatus
     *
     * @return string|null
     */
    public function mapNotificationTransactionStatusToOmsStatus(string $apiStatus): ?string;

    /**
     * @param string $apiStatus
     *
     * @return string|null
     */
    public function mapNotificationOrderStatusToOmsStatus(string $apiStatus): ?string;
}
