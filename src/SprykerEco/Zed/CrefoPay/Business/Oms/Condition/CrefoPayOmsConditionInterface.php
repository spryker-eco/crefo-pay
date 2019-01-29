<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Condition;

use Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer;

interface CrefoPayOmsConditionInterface
{
    /**
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return bool
     */
    public function check(CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer): bool;
}
