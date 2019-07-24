<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Communication\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerEco\Zed\CrefoPay\Business\CrefoPayFacadeInterface;
use SprykerEco\Zed\CrefoPay\Communication\Oms\CrefoPayOmsMapperInterface;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToRefundFacadeInterface;

class RefundOmsCommand implements CrefoPayOmsCommandByOrderInterface
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
     * @var \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToRefundFacadeInterface
     */
    protected $refundFacade;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Communication\Oms\CrefoPayOmsMapperInterface $mapper
     * @param \SprykerEco\Zed\CrefoPay\Business\CrefoPayFacadeInterface $facade
     * @param \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToRefundFacadeInterface $refundFacade
     */
    public function __construct(
        CrefoPayOmsMapperInterface $mapper,
        CrefoPayFacadeInterface $facade,
        CrefoPayToRefundFacadeInterface $refundFacade
    ) {
        $this->mapper = $mapper;
        $this->facade = $facade;
        $this->refundFacade = $refundFacade;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return void
     */
    public function execute(array $salesOrderItems, SpySalesOrder $salesOrderEntity, ReadOnlyArrayObject $data): void
    {
        $orderTransfer = $this->mapper->mapSpySalesOrderToOrderTransfer($salesOrderEntity);

        $salesOrderItemIds = array_map(
            function (SpySalesOrderItem $orderItem) {
                return $orderItem->getIdSalesOrderItem();
            },
            $salesOrderItems
        );

        $refundTransfer = $this->refundFacade
            ->calculateRefund($salesOrderItems, $salesOrderEntity);

        $this->facade
            ->executeRefundOmsCommand($refundTransfer, $orderTransfer, $salesOrderItemIds);
    }
}
