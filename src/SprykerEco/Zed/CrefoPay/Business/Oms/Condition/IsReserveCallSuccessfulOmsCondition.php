<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Condition;

use SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface;

class IsReserveCallSuccessfulOmsCondition implements CrefoPayOmsConditionInterface
{
    protected const REQUEST_TYPE = 'reserve';

    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface
     */
    protected $reader;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface $reader
     */
    public function __construct(CrefoPayReaderInterface $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function check(int $idSalesOrderItem): bool
    {
        $relationTransfer = $this->reader
            ->findPaymentCrefoPayOrderItemToCrefoPayApiLogByIdSalesOrderItemAndRequestTypeAndSuccessResult(
                $idSalesOrderItem,
                static::REQUEST_TYPE
            );

        return $relationTransfer->getIdPaymentCrefoPayOrderItemToCrefoPayApiLog() !== null;
    }
}