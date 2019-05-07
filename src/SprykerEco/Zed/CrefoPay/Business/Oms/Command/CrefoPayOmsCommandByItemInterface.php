<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command;

use Generated\Shared\Transfer\OrderTransfer;

interface CrefoPayOmsCommandByItemInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesOrderItem
     *
     * @return void
     */
    public function execute(OrderTransfer $orderTransfer, int $idSalesOrderItem): void;
}
