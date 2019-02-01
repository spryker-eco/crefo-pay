<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Communication\Oms\Condition;

use Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use SprykerEco\Zed\CrefoPay\Business\CrefoPayFacadeInterface;
use SprykerEco\Zed\CrefoPay\Communication\Oms\CrefoPayOmsMapperInterface;

class IsExpiredOmsCondition implements CrefoPayOmsConditionInterface
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
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItemEntity): bool
    {
        $crefoPayToSalesOrderItemTransfer = $this->mapper
            ->mapSpySalesOrderItemToCrefoPayToSalesOrderItemTransfer(
                $orderItemEntity,
                new CrefoPayToSalesOrderItemTransfer()
            );

        return $this->facade
            ->checkIsExpiredCondition($crefoPayToSalesOrderItemTransfer);
    }
}
