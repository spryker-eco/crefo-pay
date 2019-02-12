<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command;

interface CrefoPayOmsCommandByItemInterface
{
    /**
     * @param int $idSalesOrderItem
     *
     * @return void
     */
    public function execute(int $idSalesOrderItem): void;
}
