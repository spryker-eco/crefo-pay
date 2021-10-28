<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Condition;

use SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;

class IsMerchantPendingReceivedOmsCondition implements CrefoPayOmsConditionInterface
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
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function check(int $idSalesOrderItem): bool
    {
        $relationTransfer = $this->reader
            ->getPaymentCrefoPayOrderItemToCrefoPayNotificationByIdSalesOrderItemAndTransactionStatus(
                $idSalesOrderItem,
                $this->config->getNotificationTransactionStatusMerchantPending(),
            );

        return $relationTransfer->getIdPaymentCrefoPayOrderItemToCrefoPayNotification() !== null;
    }
}
