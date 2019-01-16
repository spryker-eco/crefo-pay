<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command;

use Generated\Shared\Transfer\CrefoPayOrderItemsDataTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class RefundOmsCommand implements CrefoPayOmsCommandInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CrefoPayOrderItemsDataTransfer $orderItemsDataTransfer
     *
     * @return void
     */
    public function execute(OrderTransfer $orderTransfer, CrefoPayOrderItemsDataTransfer $orderItemsDataTransfer): void
    {
        // TODO: Implement execute() method.
    }
}
