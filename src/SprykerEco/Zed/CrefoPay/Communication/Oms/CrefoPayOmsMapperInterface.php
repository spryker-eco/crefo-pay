<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Communication\Oms;

use Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

interface CrefoPayOmsMapperInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function mapSpySalesOrderToOrderTransfer(SpySalesOrder $orderEntity): OrderTransfer;

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer
     */
    public function mapSpySalesOrderItemToCrefoPayToSalesOrderItemTransfer(
        SpySalesOrderItem $orderItemEntity,
        CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
    ): CrefoPayToSalesOrderItemTransfer;
}
