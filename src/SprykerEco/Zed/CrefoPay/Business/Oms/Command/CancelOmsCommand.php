<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command;

use Generated\Shared\Transfer\CrefoPayToSalesOrderItemCollectionTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class CancelOmsCommand implements CrefoPayOmsCommandInterface
{
    protected $mapper;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface
     */
    protected $reader;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemCollectionTransfer $crefoPayToSalesOrderItemCollection
     *
     * @return void
     */
    public function execute(
        OrderTransfer $orderTransfer,
        CrefoPayToSalesOrderItemCollectionTransfer $crefoPayToSalesOrderItemCollection
    ): void {
    }
}
