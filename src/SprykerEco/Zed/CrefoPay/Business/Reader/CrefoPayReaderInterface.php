<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Reader;

use Generated\Shared\Transfer\CrefoPayToSalesOrderItemCollectionTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;

interface CrefoPayReaderInterface
{
    /**
     * @param int $fkSalesOrder
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function findPaymentCrefoPayByFkSalesOrder(int $fkSalesOrder): PaymentCrefoPayTransfer;

    /**
     * @param string $crefoPayOrderId
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function findPaymentCrefoPayByCrefoPayOrderId(string $crefoPayOrderId): PaymentCrefoPayTransfer;

    /**
     * @param string $crefoPayOrderId
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function findPaymentCrefoPayOrderItemsByCrefoPayOrderId(string $crefoPayOrderId): PaymentCrefoPayOrderItemCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemCollectionTransfer $crefoPayToSalesOrderItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function findPaymentCrefoPayOrderItemsByCrefoPayToSalesOrderItemCollection(
        CrefoPayToSalesOrderItemCollectionTransfer $crefoPayToSalesOrderItemCollectionTransfer
    ): PaymentCrefoPayOrderItemCollectionTransfer;
}
