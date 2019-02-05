<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Condition;

use Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer;
use SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface;

class IsCapturePendingOmsCondition implements CrefoPayOmsConditionInterface
{
    protected const REQUEST_TYPE = 'capture';

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
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return bool
     */
    public function check(CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer): bool
    {
        $relationTransfer = $this->reader
            ->findPaymentCrefoPayOrderItemToCrefoPayApiLogByIdSalesOrderItemAndRequestType(
                $crefoPayToSalesOrderItemTransfer->getIdSalesOrderItem(),
                static::REQUEST_TYPE
            );

        return $relationTransfer->getIdPaymentCrefoPayOrderItemToCrefoPayApiLog() !== null;
    }
}
