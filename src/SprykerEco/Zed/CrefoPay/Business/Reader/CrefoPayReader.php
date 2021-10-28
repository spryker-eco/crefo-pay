<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Reader;

use Generated\Shared\Transfer\CrefoPayToSalesOrderItemsCollectionTransfer;
use Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;
use SprykerEco\Zed\CrefoPay\Persistence\CrefoPayRepositoryInterface;

class CrefoPayReader implements CrefoPayReaderInterface
{
    /**
     * @var array
     */
    protected const SUCCESS_RESULT_CODES = [0, 1];

    /**
     * @var \SprykerEco\Zed\CrefoPay\Persistence\CrefoPayRepositoryInterface
     */
    protected $repository;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Persistence\CrefoPayRepositoryInterface $repository
     */
    public function __construct(CrefoPayRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function getPaymentCrefoPayByIdSalesOrder(int $idSalesOrder): PaymentCrefoPayTransfer
    {
        return $this->repository->findPaymentCrefoPayByIdSalesOrder($idSalesOrder) ?? new PaymentCrefoPayTransfer();
    }

    /**
     * @param string $crefoPayOrderId
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function getPaymentCrefoPayByCrefoPayOrderId(string $crefoPayOrderId): PaymentCrefoPayTransfer
    {
        return $this->repository->findPaymentCrefoPayByCrefoPayOrderId($crefoPayOrderId) ?? new PaymentCrefoPayTransfer();
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function getPaymentCrefoPayByIdSalesOrderItem(int $idSalesOrderItem): PaymentCrefoPayTransfer
    {
        return $this->repository->findPaymentCrefoPayByIdSalesOrderItem($idSalesOrderItem) ?? new PaymentCrefoPayTransfer();
    }

    /**
     * @param string $crefoPayOrderId
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function getPaymentCrefoPayOrderItemCollectionByCrefoPayOrderId(string $crefoPayOrderId): PaymentCrefoPayOrderItemCollectionTransfer
    {
        return $this->repository->getPaymentCrefoPayOrderItemCollectionByCrefoPayOrderId($crefoPayOrderId);
    }

    /**
     * @param string $captureId
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function getPaymentCrefoPayOrderItemCollectionByCaptureId(string $captureId): PaymentCrefoPayOrderItemCollectionTransfer
    {
        return $this->repository->getPaymentCrefoPayOrderItemCollectionByCaptureId($captureId);
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemsCollectionTransfer $crefoPayToSalesOrderItemsCollection
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function getPaymentCrefoPayOrderItemCollectionByCrefoPayToSalesOrderItemsCollection(
        CrefoPayToSalesOrderItemsCollectionTransfer $crefoPayToSalesOrderItemsCollection
    ): PaymentCrefoPayOrderItemCollectionTransfer {
        $salesOrderItemIds = array_map(
            function (CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer) {
                return $crefoPayToSalesOrderItemTransfer->getIdSalesOrderItem();
            },
            $crefoPayToSalesOrderItemsCollection->getCrefoPayToSalesOrderItems()->getArrayCopy(),
        );

        return $this->repository->getPaymentCrefoPayOrderItemCollectionBySalesOrderItemIds($salesOrderItemIds);
    }

    /**
     * @param array<int> $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function getPaymentCrefoPayOrderItemCollectionBySalesOrderItemIds(array $salesOrderItemIds): PaymentCrefoPayOrderItemCollectionTransfer
    {
        return $this->repository->getPaymentCrefoPayOrderItemCollectionBySalesOrderItemIds($salesOrderItemIds);
    }

    /**
     * @param int $idSalesOrderItem
     * @param string $apiLogRequestType
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer
     */
    public function getPaymentCrefoPayOrderItemToCrefoPayApiLogByIdSalesOrderItemAndRequestTypeAndSuccessResult(
        int $idSalesOrderItem,
        string $apiLogRequestType
    ): PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer {
        return $this->repository
            ->findPaymentCrefoPayOrderItemToCrefoPayApiLogByIdSalesOrderItemAndRequestTypeAndResultCodes(
                $idSalesOrderItem,
                $apiLogRequestType,
                static::SUCCESS_RESULT_CODES,
            )
            ?? new PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer();
    }

    /**
     * @param int $idSalesOrderItem
     * @param string $notificationTransactionStatus
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer
     */
    public function getPaymentCrefoPayOrderItemToCrefoPayNotificationByIdSalesOrderItemAndTransactionStatus(
        int $idSalesOrderItem,
        string $notificationTransactionStatus
    ): PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer {
        return $this->repository
            ->findPaymentCrefoPayOrderItemToCrefoPayNotificationByIdSalesOrderItemAndTransactionStatus(
                $idSalesOrderItem,
                $notificationTransactionStatus,
            )
            ?? new PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer();
    }

    /**
     * @param int $idSalesOrderItem
     * @param string $notificationOredrStatus
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer
     */
    public function getPaymentCrefoPayOrderItemToCrefoPayNotificationByIdSalesOrderItemAndOrderStatus(
        int $idSalesOrderItem,
        string $notificationOredrStatus
    ): PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer {
        return $this->repository
            ->findPaymentCrefoPayOrderItemToCrefoPayNotificationByIdSalesOrderItemAndOrderStatus(
                $idSalesOrderItem,
                $notificationOredrStatus,
            )
            ?? new PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer();
    }
}
