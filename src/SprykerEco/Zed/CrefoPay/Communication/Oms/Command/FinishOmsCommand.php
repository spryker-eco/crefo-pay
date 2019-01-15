<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Communication\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;

class FinishOmsCommand extends BaseOmsCommand implements CrefoPayOmsCommandInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return void
     */
    public function execute(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data): void
    {
        $this->facade
            ->executeFinishCommand(
                $this->getOrderTransfer($orderEntity),
                $this->createOrderItemsDataTransfer($orderItems)
            );
    }
}
