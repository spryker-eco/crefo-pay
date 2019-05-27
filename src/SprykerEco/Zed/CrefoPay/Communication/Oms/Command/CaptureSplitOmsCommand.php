<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Communication\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerEco\Zed\CrefoPay\Business\CrefoPayFacadeInterface;
use SprykerEco\Zed\CrefoPay\Communication\Oms\CrefoPayOmsMapperInterface;

class CaptureSplitOmsCommand implements CrefoPayOmsCommandByItemInterface
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
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItem
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return void
     */
    public function execute(SpySalesOrderItem $salesOrderItem, ReadOnlyArrayObject $data): void
    {
        $orderTransfer = $this->mapper
            ->mapSpySalesOrderToOrderTransfer($salesOrderItem->getOrder());

        $this->facade
            ->executeCaptureOmsCommand($orderTransfer, [$salesOrderItem->getIdSalesOrderItem()]);
    }
}