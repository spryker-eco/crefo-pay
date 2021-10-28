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
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemQuery;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemToCrefoPayApiLogQuery;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemToCrefoPayNotificationQuery;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use SprykerEco\Zed\CrefoPay\Persistence\Mapper\CrefoPayPersistenceMapper;

/**
 * @method \SprykerEco\Zed\CrefoPay\Persistence\CrefoPayPersistenceFactory getFactory()
 */
class CrefoPayRepository extends AbstractRepository implements CrefoPayRepositoryInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer|null
     */
    public function findPaymentCrefoPayByIdSalesOrder(int $idSalesOrder): ?PaymentCrefoPayTransfer
    {
        $paymentCrefoPayEntity = $this->getPaymentCrefoPayQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->findOne();

        if ($paymentCrefoPayEntity === null) {
            return null;
        }

        return $this->getMapper()
            ->mapEntityToPaymentCrefoPayTransfer($paymentCrefoPayEntity, new PaymentCrefoPayTransfer());
    }

    /**
     * @param string $crefoPayOrderId
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer|null
     */
    public function findPaymentCrefoPayByCrefoPayOrderId(string $crefoPayOrderId): ?PaymentCrefoPayTransfer
    {
        $paymentCrefoPayEntity = $this->getPaymentCrefoPayQuery()
            ->filterByCrefoPayOrderId($crefoPayOrderId)
            ->findOne();

        if ($paymentCrefoPayEntity === null) {
            return null;
        }

        return $this->getMapper()
            ->mapEntityToPaymentCrefoPayTransfer($paymentCrefoPayEntity, new PaymentCrefoPayTransfer());
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer|null
     */
    public function findPaymentCrefoPayByIdSalesOrderItem(int $idSalesOrderItem): ?PaymentCrefoPayTransfer
    {
        /** @var \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPay|null $paymentCrefoPayEntity */
        $paymentCrefoPayEntity = $this->getPaymentCrefoPayQuery()
            ->useSpyPaymentCrefoPayOrderItemQuery()
                ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->endUse()
            ->findOne();

        if ($paymentCrefoPayEntity === null) {
            return null;
        }

        return $this->getMapper()
            ->mapEntityToPaymentCrefoPayTransfer($paymentCrefoPayEntity, new PaymentCrefoPayTransfer());
    }

    /**
     * @param string $crefoPayOrderId
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function getPaymentCrefoPayOrderItemCollectionByCrefoPayOrderId(string $crefoPayOrderId): PaymentCrefoPayOrderItemCollectionTransfer
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItem> $paymentCrefoPayOrderItemEntities */
        $paymentCrefoPayOrderItemEntities = $this->getPaymentCrefoPayOrderItemQuery()
            ->useSpyPaymentCrefoPayQuery()
                ->filterByCrefoPayOrderId($crefoPayOrderId)
            ->endUse()
            ->find();

        return $this->getMapper()
            ->mapOrderItemEntitiesToOrderItemCollection(
                $paymentCrefoPayOrderItemEntities,
                new PaymentCrefoPayOrderItemCollectionTransfer(),
            );
    }

    /**
     * @param string $captureId
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function getPaymentCrefoPayOrderItemCollectionByCaptureId(string $captureId): PaymentCrefoPayOrderItemCollectionTransfer
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItem> $paymentCrefoPayOrderItemEntities */
        $paymentCrefoPayOrderItemEntities = $this->getPaymentCrefoPayOrderItemQuery()
            ->filterByCaptureId($captureId)
            ->find();

        return $this->getMapper()
            ->mapOrderItemEntitiesToOrderItemCollection(
                $paymentCrefoPayOrderItemEntities,
                new PaymentCrefoPayOrderItemCollectionTransfer(),
            );
    }

    /**
     * @param array<int> $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function getPaymentCrefoPayOrderItemCollectionBySalesOrderItemIds(array $salesOrderItemIds): PaymentCrefoPayOrderItemCollectionTransfer
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItem> $paymentCrefoPayOrderItemEntities */
        $paymentCrefoPayOrderItemEntities = $this->getPaymentCrefoPayOrderItemQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds)
            ->find();

        return $this->getMapper()
            ->mapOrderItemEntitiesToOrderItemCollection(
                $paymentCrefoPayOrderItemEntities,
                new PaymentCrefoPayOrderItemCollectionTransfer(),
            );
    }

    /**
     * @param int $idSalesOrderItem
     * @param string $apiLogRequestType
     * @param array<int> $resultCodes
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer|null
     */
    public function findPaymentCrefoPayOrderItemToCrefoPayApiLogByIdSalesOrderItemAndRequestTypeAndResultCodes(
        int $idSalesOrderItem,
        string $apiLogRequestType,
        array $resultCodes
    ): ?PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer {
        $paymentCrefoPayOrderItemToCrefoPayApiLogEntity = $this->getPaymentCrefoPayOrderItemToCrefoPayApiLogQuery()
            ->useSpyPaymentCrefoPayOrderItemQuery()
                ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->endUse()
            ->useSpyPaymentCrefoPayApiLogQuery()
                ->filterByRequestType($apiLogRequestType)
                ->filterByResultCode_In($resultCodes)
            ->endUse()
            ->findOne();

        if ($paymentCrefoPayOrderItemToCrefoPayApiLogEntity === null) {
            return null;
        }

        return $this->getMapper()
            ->mapEntityToPaymentCrefoPayOrderItemToCrefoPayApiLogTransfer(
                $paymentCrefoPayOrderItemToCrefoPayApiLogEntity,
                new PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer(),
            );
    }

    /**
     * @param int $idSalesOrderItem
     * @param string $notificationTransactionStatus
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer|null
     */
    public function findPaymentCrefoPayOrderItemToCrefoPayNotificationByIdSalesOrderItemAndTransactionStatus(
        int $idSalesOrderItem,
        string $notificationTransactionStatus
    ): ?PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer {
        $paymentCrefoPayOrderItemToCrefoPayNotificationEntity = $this->getPaymentCrefoPayOrderItemToCrefoPayNotificationQuery()
            ->useSpyPaymentCrefoPayOrderItemQuery()
                ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->endUse()
            ->useSpyPaymentCrefoPayNotificationQuery()
                ->filterByTransactionStatus($notificationTransactionStatus)
            ->endUse()
            ->findOne();

        if ($paymentCrefoPayOrderItemToCrefoPayNotificationEntity === null) {
            return null;
        }

        return $this->getMapper()
            ->mapEntityToPaymentCrefoPayOrderItemToCrefoPayNotificationTransfer(
                $paymentCrefoPayOrderItemToCrefoPayNotificationEntity,
                new PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer(),
            );
    }

    /**
     * @param int $idSalesOrderItem
     * @param string $notificationOrderStatus
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer|null
     */
    public function findPaymentCrefoPayOrderItemToCrefoPayNotificationByIdSalesOrderItemAndOrderStatus(
        int $idSalesOrderItem,
        string $notificationOrderStatus
    ): ?PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer {
        $paymentCrefoPayOrderItemToCrefoPayNotificationEntity = $this->getPaymentCrefoPayOrderItemToCrefoPayNotificationQuery()
            ->useSpyPaymentCrefoPayOrderItemQuery()
                ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->endUse()
            ->useSpyPaymentCrefoPayNotificationQuery()
                ->filterByOrderStatus($notificationOrderStatus)
            ->endUse()
            ->findOne();

        if ($paymentCrefoPayOrderItemToCrefoPayNotificationEntity === null) {
            return null;
        }

        return $this->getMapper()
            ->mapEntityToPaymentCrefoPayOrderItemToCrefoPayNotificationTransfer(
                $paymentCrefoPayOrderItemToCrefoPayNotificationEntity,
                new PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer(),
            );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Persistence\Mapper\CrefoPayPersistenceMapper
     */
    protected function getMapper(): CrefoPayPersistenceMapper
    {
        return $this->getFactory()->createCrefoPayPersistenceMapper();
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
}
