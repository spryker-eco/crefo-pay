<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Persistence;

use Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;

interface CrefoPayRepositoryInterface
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
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function findPaymentCrefoPayByIdSalesOrderItem(int $idSalesOrderItem): PaymentCrefoPayTransfer;

    /**
     * @param string $crefoPayOrderId
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function findPaymentCrefoPayOrderItemsByCrefoPayOrderId(string $crefoPayOrderId): PaymentCrefoPayOrderItemCollectionTransfer;

    /**
     * @param string $captureId
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function findPaymentCrefoPayOrderItemsByCaptureId(string $captureId): PaymentCrefoPayOrderItemCollectionTransfer;

    /**
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function findPaymentCrefoPayOrderItemsBySalesOrderItemIds(array $salesOrderItemIds): PaymentCrefoPayOrderItemCollectionTransfer;

    /**
     * @param int $idSalesOrderItem
     * @param string $apiLogRequestType
     * @param int[] $resultCodes
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer
     */
    public function findPaymentCrefoPayOrderItemToCrefoPayApiLogByIdSalesOrderItemAndRequestTypeAndResultCodes(
        int $idSalesOrderItem,
        string $apiLogRequestType,
        array $resultCodes
    ): PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer;

    /**
     * @param int $idSalesOrderItem
     * @param string $notificationTransactionStatus
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer
     */
    public function findPaymentCrefoPayOrderItemToCrefoPayNotificationByIdSalesOrderItemAndTransactionStatus(
        int $idSalesOrderItem,
        string $notificationTransactionStatus
    ): PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer;

    /**
     * @param int $idSalesOrderItem
     * @param string $notificationOrderStatus
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer
     */
    public function findPaymentCrefoPayOrderItemToCrefoPayNotificationByIdSalesOrderItemAndOrderStatus(
        int $idSalesOrderItem,
        string $notificationOrderStatus
    ): PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer;
}
