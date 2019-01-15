<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Communication\Oms\Command;

use Generated\Shared\Transfer\CrefoPayOrderItemsDataTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use SprykerEco\Zed\CrefoPay\Business\CrefoPayFacadeInterface;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCalculationFacadeInterface;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToSalesFacadeInterface;

class BaseOmsCommand
{
    /**
     * @var \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCalculationFacadeInterface
     */
    protected $calculationFacade;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\CrefoPayFacadeInterface
     */
    protected $facade;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToSalesFacadeInterface $salesFacade
     * @param \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCalculationFacadeInterface $calculationFacade
     * @param \SprykerEco\Zed\CrefoPay\Business\CrefoPayFacadeInterface $facade
     */
    public function __construct(
        CrefoPayToSalesFacadeInterface $salesFacade,
        CrefoPayToCalculationFacadeInterface $calculationFacade,
        CrefoPayFacadeInterface $facade
    ) {
        $this->salesFacade = $salesFacade;
        $this->calculationFacade = $calculationFacade;
        $this->facade = $facade;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer(SpySalesOrder $orderEntity): OrderTransfer
    {
        $orderTransfer = $this->salesFacade
            ->getOrderByIdSalesOrder($orderEntity->getIdSalesOrder());

        return $this->calculationFacade
            ->recalculateOrder($orderTransfer);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     *
     * @return \Generated\Shared\Transfer\CrefoPayOrderItemsDataTransfer
     */
    protected function createOrderItemsDataTransfer(array $orderItems): CrefoPayOrderItemsDataTransfer
    {
        $orderItemIds = array_map(
            function (SpySalesOrderItem $orderItem) {
                return $orderItem->getIdSalesOrderItem();
            },
            $orderItems
        );

        return (new CrefoPayOrderItemsDataTransfer())
            ->setOrderItemIds($orderItemIds);
    }
}
