<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Communication\Oms\Command;

use Generated\Shared\Transfer\CrefoPayToSalesOrderItemsCollectionTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerEco\Zed\CrefoPay\Business\CrefoPayFacadeInterface;
use SprykerEco\Zed\CrefoPay\Communication\Oms\CrefoPayOmsMapperInterface;

class CancelOmsCommand implements CrefoPayOmsCommandInterface
{
    /**
     * @var \SprykerEco\Zed\CrefoPay\Communication\Oms\CrefoPayOmsMapperInterface
     */
    protected $mapper;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\CrefoPayFacadeInterface
     */
    protected $facade;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Communication\Oms\CrefoPayOmsMapperInterface $mapper
     * @param \SprykerEco\Zed\CrefoPay\Business\CrefoPayFacadeInterface $facade
     */
    public function __construct(
        CrefoPayOmsMapperInterface $mapper,
        CrefoPayFacadeInterface $facade
    ) {
        $this->mapper = $mapper;
        $this->facade = $facade;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return void
     */
    public function execute(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data): void
    {
        $orderTransfer = $this->mapper->mapSpySalesOrderToOrderTransfer($orderEntity);
        $crefoPayToSalesOrderItemsCollection = $this->mapper
            ->mapSpySalesOrderItemsToCrefoPayToSalesOrderItemsCollection(
                $orderItems,
                new CrefoPayToSalesOrderItemsCollectionTransfer()
            );

        $this->facade
            ->executeCancelCommand(
                $orderTransfer,
                $crefoPayToSalesOrderItemsCollection
            );
    }
}
