<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command;

use Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer;

interface CrefoPayOmsCommandByItemInterface
{
    /**
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return void
     */
    public function execute(CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer): void;
}
