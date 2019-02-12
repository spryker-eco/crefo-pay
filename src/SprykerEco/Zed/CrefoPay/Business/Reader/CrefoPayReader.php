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
     * @param int $fkSalesOrder
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function findPaymentCrefoPayByFkSalesOrder(int $fkSalesOrder): PaymentCrefoPayTransfer
    {
        return $this->repository->findPaymentCrefoPayByFkSalesOrder($fkSalesOrder);
    }

    /**
     * @param string $crefoPayOrderId
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function findPaymentCrefoPayByCrefoPayOrderId(string $crefoPayOrderId): PaymentCrefoPayTransfer
    {
        return $this->repository->findPaymentCrefoPayByCrefoPayOrderId($crefoPayOrderId);
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function findPaymentCrefoPayByIdSalesOrderItem(int $idSalesOrderItem): PaymentCrefoPayTransfer
    {
        return $this->repository->findPaymentCrefoPayByIdSalesOrderItem($idSalesOrderItem);
    }

    /**
     * @param string $crefoPayOrderId
     * @param string|null $captureId
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function findPaymentCrefoPayOrderItemsByCrefoPayOrderIdAndCaptureId(
        string $crefoPayOrderId,
        ?string $captureId = null
    ): PaymentCrefoPayOrderItemCollectionTransfer {
        return $this->repository->findPaymentCrefoPayOrderItemsByCrefoPayOrderIdAndCaptureId($crefoPayOrderId);
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemsCollectionTransfer $crefoPayToSalesOrderItemsCollection
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function findPaymentCrefoPayOrderItemsByCrefoPayToSalesOrderItemsCollection(
        CrefoPayToSalesOrderItemsCollectionTransfer $crefoPayToSalesOrderItemsCollection
    ): PaymentCrefoPayOrderItemCollectionTransfer {
        $salesOrderItemIds = array_map(
            function (CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer) {
                return $crefoPayToSalesOrderItemTransfer->getIdSalesOrderItem();
            },
            $crefoPayToSalesOrderItemsCollection->getCrefoPayToSalesOrderItems()->getArrayCopy()
        );

        return $this->repository->findPaymentCrefoPayOrderItemsBySalesOrderItemIds($salesOrderItemIds);
    }

    /**
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function findPaymentCrefoPayOrderItemsBySalesOrderItemIds(array $salesOrderItemIds): PaymentCrefoPayOrderItemCollectionTransfer
    {
        return $this->repository->findPaymentCrefoPayOrderItemsBySalesOrderItemIds($salesOrderItemIds);
    }

    /**
     * @param int $idSalesOrderItem
     * @param string $apiLogRequestType
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer
     */
    public function findPaymentCrefoPayOrderItemToCrefoPayApiLogByIdSalesOrderItemAndRequestType(
        int $idSalesOrderItem,
        string $apiLogRequestType
    ): PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer {
        return $this->repository
            ->findPaymentCrefoPayOrderItemToCrefoPayApiLogByIdSalesOrderItemAndRequestType(
                $idSalesOrderItem,
                $apiLogRequestType
            );
    }

    /**
     * @param int $idSalesOrderItem
     * @param string $notificationTransactionStatus
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer
     */
    public function findPaymentCrefoPayOrderItemToCrefoPayNotificationByIdSalesOrderItemAndTransactionStatus(
        int $idSalesOrderItem,
        string $notificationTransactionStatus
    ): PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer {
        return $this->repository
            ->findPaymentCrefoPayOrderItemToCrefoPayNotificationByIdSalesOrderItemAndTransactionStatus(
                $idSalesOrderItem,
                $notificationTransactionStatus
            );
    }

    /**
     * @param int $idSalesOrderItem
     * @param string $notificationOredrStatus
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer
     */
    public function findPaymentCrefoPayOrderItemToCrefoPayNotificationByIdSalesOrderItemAndOrderStatus(
        int $idSalesOrderItem,
        string $notificationOredrStatus
    ): PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer {
        return $this->repository
            ->findPaymentCrefoPayOrderItemToCrefoPayNotificationByIdSalesOrderItemAndOrderStatus(
                $idSalesOrderItem,
                $notificationOredrStatus
            );
    }
}
