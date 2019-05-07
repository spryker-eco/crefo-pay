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
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToRefundFacadeInterface;

class RefundOmsCommand implements CrefoPayOmsCommandByItemInterface
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
            ->executeRefundOmsCommand($orderTransfer, $salesOrderItem->getIdSalesOrderItem());

        $refundTransfer = $this->refundFacade
            ->calculateRefund([$salesOrderItem], $salesOrderItem->getOrder());

        if ($refundTransfer->getAmount() > 0) {
            $this->refundFacade->saveRefund($refundTransfer);
        }
    }
}
