<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Condition;

use Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer;
use SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;

class IsExpiredOmsCondition implements CrefoPayOmsConditionInterface
{
    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface
     */
    protected $reader;

    /**
     * @var \SprykerEco\Zed\CrefoPay\CrefoPayConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface $reader
     * @param \SprykerEco\Zed\CrefoPay\CrefoPayConfig $config
     */
    public function __construct(
        CrefoPayReaderInterface $reader,
        CrefoPayConfig $config
    ) {
        $this->reader = $reader;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return bool
     */
    public function check(CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer): bool
    {
        $relationTransfer = $this->reader
            ->findPaymentCrefoPayOrderItemToCrefoPayNotificationByIdSalesOrderItemAndTransactionStatus(
                $crefoPayToSalesOrderItemTransfer->getIdSalesOrderItem(),
                $this->config->getNotificationTransactionStatusExpired()
            );

        return $relationTransfer->getIdPaymentCrefoPayOrderItemToCrefoPayNotification() !== null;
    }
}
