<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Persistence;

use Generated\Shared\Transfer\PaymentCrefoPayTransfer;

interface CrefoPayRepositoryInterface
{
    /**
     * @param string $reference
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function getPaymentCrefoPayByReference(string $reference): PaymentCrefoPayTransfer;

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function getPaymentCrefoPayByIdSalesOrder(int $idSalesOrder): PaymentCrefoPayTransfer;

    /**
     * @param string $pspReference
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function getPaymentCrefoPayByPspReference(string $pspReference): PaymentCrefoPayTransfer;

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer[]
     */
    public function getAllPaymentCrefoPayOrderItemsByIdSalesOrder(int $idSalesOrder): array;

    /**
     * @param int[] $orderItemIds
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer[]
     */
    public function getOrderItemsByIdsSalesOrderItems(array $orderItemIds): array;

    /**
     * @param int $idSalesOrder
     * @param int[] $orderItemIds
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer[]
     */
    public function getRemainingPaymentCrefoPayOrderItems(int $idSalesOrder, array $orderItemIds): array;

    /**
     * @param int $idSalesOrder
     * @param int[] $orderItemIds
     *
     * @return int[]
     */
    public function getRemainingSalesOrderItemIds(int $idSalesOrder, array $orderItemIds): array;
}
