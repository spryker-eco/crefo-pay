<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemQuery;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemToCrefoPayApiLogQuery;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemToCrefoPayNotificationQuery;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerEco\Zed\CrefoPay\Persistence\CrefoPayPersistenceFactory getFactory()
 */
class CrefoPayRepository extends AbstractRepository implements CrefoPayRepositoryInterface
{
    /**
     * @param int $fkSalesOrder
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function findPaymentCrefoPayByFkSalesOrder(int $fkSalesOrder): PaymentCrefoPayTransfer
    {
        $query = $this->getPaymentCrefoPayQuery()
            ->filterByFkSalesOrder($fkSalesOrder);

        $entityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        if ($entityTransfer === null) {
            return new PaymentCrefoPayTransfer();
        }

        return $this->getFactory()
            ->createCrefoPayPersistenceMapper()
            ->mapEntityTransferToPaymentCrefoPayTransfer($entityTransfer, new PaymentCrefoPayTransfer());
    }

    /**
     * @param string $crefoPayOrderId
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function findPaymentCrefoPayByCrefoPayOrderId(string $crefoPayOrderId): PaymentCrefoPayTransfer
    {
        $query = $this->getPaymentCrefoPayQuery()
            ->filterByCrefoPayOrderId($crefoPayOrderId);

        $entityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        if ($entityTransfer === null) {
            return new PaymentCrefoPayTransfer();
        }

        return $this->getFactory()
            ->createCrefoPayPersistenceMapper()
            ->mapEntityTransferToPaymentCrefoPayTransfer($entityTransfer, new PaymentCrefoPayTransfer());
    }

    /**
     * @param string $crefoPayOrderId
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function findPaymentCrefoPayOrderItemsByCrefoPayOrderId(string $crefoPayOrderId): PaymentCrefoPayOrderItemCollectionTransfer
    {
        $query = $this->getPaymentCrefoPayOrderItemQuery()
            ->useSpyPaymentCrefoPayQuery()
                ->filterByCrefoPayOrderId($crefoPayOrderId)
            ->endUse();

        $entityTransfers = $this->buildQueryFromCriteria($query)->find();

        $mapper = $this->getFactory()->createCrefoPayPersistenceMapper();
        $result = new ArrayObject();

        foreach ($entityTransfers as $entityTransfer) {
            $paymentCrefoPayOrderItemTransfer = $mapper->mapEntityTransferToPaymentCrefoPayOrderItemTransfer(
                $entityTransfer,
                new PaymentCrefoPayOrderItemTransfer()
            );
            $result->append($paymentCrefoPayOrderItemTransfer);
        }

        return (new PaymentCrefoPayOrderItemCollectionTransfer())
            ->setCrefoPayOrderItems($result);
    }

    /**
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function findPaymentCrefoPayOrderItemsBySalesOrderItemIds(array $salesOrderItemIds): PaymentCrefoPayOrderItemCollectionTransfer
    {
        $query = $this->getPaymentCrefoPayOrderItemQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds);

        $entityTransfers = $this->buildQueryFromCriteria($query)->find();

        $mapper = $this->getFactory()->createCrefoPayPersistenceMapper();
        $result = new ArrayObject();

        foreach ($entityTransfers as $entityTransfer) {
            $paymentCrefoPayOrderItemTransfer = $mapper->mapEntityTransferToPaymentCrefoPayOrderItemTransfer(
                $entityTransfer,
                new PaymentCrefoPayOrderItemTransfer()
            );
            $result->append($paymentCrefoPayOrderItemTransfer);
        }

        return (new PaymentCrefoPayOrderItemCollectionTransfer())
            ->setCrefoPayOrderItems($result);
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
        $query = $this->getPaymentCrefoPayOrderItemToCrefoPayApiLogQuery()
            ->useSpyPaymentCrefoPayOrderItemQuery()
                ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->endUse()
            ->useSpyPaymentCrefoPayApiLogQuery()
                ->filterByRequestType($apiLogRequestType)
                ->filterByResultCode(0)
            ->endUse();

        $entityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        if ($entityTransfer === null) {
            return new PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer();
        }

        return $this->getFactory()
            ->createCrefoPayPersistenceMapper()
            ->mapEntityTransferToPaymentCrefoPayOrderItemToCrefoPayApiLogTransfer(
                $entityTransfer,
                new PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer()
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
        $query = $this->getPaymentCrefoPayOrderItemToCrefoPayNotificationQuery()
            ->useSpyPaymentCrefoPayOrderItemQuery()
                ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->endUse()
            ->useSpyPaymentCrefoPayNotificationQuery()
                ->filterByTransactionStatus($notificationTransactionStatus)
            ->endUse();

        $entityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        if ($entityTransfer === null) {
            return new PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer();
        }

        return $this->getFactory()
            ->createCrefoPayPersistenceMapper()
            ->mapEntityTransferToPaymentCrefoPayOrderItemToCrefoPayNotificationTransfer(
                $entityTransfer,
                new PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer()
            );
    }

    /**
     * @return \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayQuery
     */
    protected function getPaymentCrefoPayQuery(): SpyPaymentCrefoPayQuery
    {
        return $this->getFactory()->createPaymentCrefoPayQuery();
    }

    /**
     * @return \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemQuery
     */
    protected function getPaymentCrefoPayOrderItemQuery(): SpyPaymentCrefoPayOrderItemQuery
    {
        return $this->getFactory()->createPaymentCrefoPayOrderItemQuery();
    }

    /**
     * @return \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemToCrefoPayApiLogQuery
     */
    protected function getPaymentCrefoPayOrderItemToCrefoPayApiLogQuery(): SpyPaymentCrefoPayOrderItemToCrefoPayApiLogQuery
    {
        return $this->getFactory()->createPaymentCrefoPayOrderItemToCrefoPayApiLogQuery();
    }

    /**
     * @return \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemToCrefoPayNotificationQuery
     */
    protected function getPaymentCrefoPayOrderItemToCrefoPayNotificationQuery(): SpyPaymentCrefoPayOrderItemToCrefoPayNotificationQuery
    {
        return $this->getFactory()->createPaymentCrefoPayOrderItemToCrefoPayNotificationQuery();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected function getSalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return $this->getFactory()->createSpySalesOrderItemQuery();
    }
}
