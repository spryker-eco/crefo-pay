<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Dependency\Facade;

interface CrefoPayToOmsFacadeInterface
{
    /**
     * @param array $logContext
     *
     * @return int
     */
    public function checkConditions(array $logContext = []);

    /**
     * @param string $eventId
     * @param array $orderItemIds
     * @param array $data
     *
     * @return array|null
     */
    public function triggerEventForOrderItems($eventId, array $orderItemIds, array $data = []);
}
