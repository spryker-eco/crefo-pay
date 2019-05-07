<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Communication\Oms;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCalculationFacadeInterface;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToSalesFacadeInterface;

class CrefoPayOmsMapper implements CrefoPayOmsMapperInterface
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
     * @param \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToSalesFacadeInterface $salesFacade
     * @param \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCalculationFacadeInterface $calculationFacade
     */
    public function __construct(
        CrefoPayToSalesFacadeInterface $salesFacade,
        CrefoPayToCalculationFacadeInterface $calculationFacade
    ) {
        $this->salesFacade = $salesFacade;
        $this->calculationFacade = $calculationFacade;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function mapSpySalesOrderToOrderTransfer(SpySalesOrder $orderEntity): OrderTransfer
    {
        $orderTransfer = $this->salesFacade
            ->getOrderByIdSalesOrder($orderEntity->getIdSalesOrder());

        return $this->calculationFacade
            ->recalculateOrder($orderTransfer);
    }
}
