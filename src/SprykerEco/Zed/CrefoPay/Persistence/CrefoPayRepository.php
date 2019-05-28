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
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use SprykerEco\Zed\CrefoPay\Persistence\Mapper\CrefoPayPersistenceMapper;

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
        $paymentCrefoPayEntity = $this->getPaymentCrefoPayQuery()
            ->filterByFkSalesOrder($fkSalesOrder)
            ->findOne();

        $paymentCrefoPayTransfer = new PaymentCrefoPayTransfer();

        if ($paymentCrefoPayEntity === null) {
            return $paymentCrefoPayTransfer;
        }

        return $this->getMapper()
            ->mapEntityToPaymentCrefoPayTransfer($paymentCrefoPayEntity, $paymentCrefoPayTransfer);
    }

    /**
     * @param string $crefoPayOrderId
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function findPaymentCrefoPayByCrefoPayOrderId(string $crefoPayOrderId): PaymentCrefoPayTransfer
    {
        $paymentCrefoPayEntity = $this->getPaymentCrefoPayQuery()
            ->filterByCrefoPayOrderId($crefoPayOrderId)
            ->findOne();

        $paymentCrefoPayTransfer = new PaymentCrefoPayTransfer();

        if ($paymentCrefoPayEntity === null) {
            return $paymentCrefoPayTransfer;
        }

        return $this->getMapper()
            ->mapEntityToPaymentCrefoPayTransfer($paymentCrefoPayEntity, $paymentCrefoPayTransfer);
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function findPaymentCrefoPayByIdSalesOrderItem(int $idSalesOrderItem): PaymentCrefoPayTransfer
    {
        /** @var \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPay|null $paymentCrefoPayEntity */
        $paymentCrefoPayEntity = $this->getPaymentCrefoPayQuery()
            ->useSpyPaymentCrefoPayOrderItemQuery()
                ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->endUse()
            ->findOne();

        $paymentCrefoPayTransfer = new PaymentCrefoPayTransfer();

        if ($paymentCrefoPayEntity === null) {
            return $paymentCrefoPayTransfer;
        }

        return $this->getMapper()
            ->mapEntityToPaymentCrefoPayTransfer($paymentCrefoPayEntity, $paymentCrefoPayTransfer);
    }

    /**
     * @param string $crefoPayOrderId
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function findPaymentCrefoPayOrderItemsByCrefoPayOrderId(string $crefoPayOrderId): PaymentCrefoPayOrderItemCollectionTransfer
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItem[] $paymentCrefoPayOrderItemEntities */
        $paymentCrefoPayOrderItemEntities = $this->getPaymentCrefoPayOrderItemQuery()
            ->useSpyPaymentCrefoPayQuery()
                ->filterByCrefoPayOrderId($crefoPayOrderId)
            ->endUse()
            ->find();

        return $this->mapOrderItemEntitiesToOrderItemCollection(
            $paymentCrefoPayOrderItemEntities,
            new PaymentCrefoPayOrderItemCollectionTransfer()
        );
    }

    /**
     * @param string $captureId
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function findPaymentCrefoPayOrderItemsByCaptureId(string $captureId): PaymentCrefoPayOrderItemCollectionTransfer
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItem[] $paymentCrefoPayOrderItemEntities */
        $paymentCrefoPayOrderItemEntities = $this->getPaymentCrefoPayOrderItemQuery()
            ->filterByCaptureId($captureId)
            ->find();

        return $this->mapOrderItemEntitiesToOrderItemCollection(
            $paymentCrefoPayOrderItemEntities,
            new PaymentCrefoPayOrderItemCollectionTransfer()
        );
    }

    /**
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function findPaymentCrefoPayOrderItemsBySalesOrderItemIds(array $salesOrderItemIds): PaymentCrefoPayOrderItemCollectionTransfer
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItem[] $paymentCrefoPayOrderItemEntities */
        $paymentCrefoPayOrderItemEntities = $this->getPaymentCrefoPayOrderItemQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds)
            ->find();

        return $this->mapOrderItemEntitiesToOrderItemCollection(
            $paymentCrefoPayOrderItemEntities,
            new PaymentCrefoPayOrderItemCollectionTransfer()
        );
    }

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
    ): PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer {
        $paymentCrefoPayOrderItemToCrefoPayApiLogEntity = $this->getPaymentCrefoPayOrderItemToCrefoPayApiLogQuery()
            ->useSpyPaymentCrefoPayOrderItemQuery()
                ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->endUse()
            ->useSpyPaymentCrefoPayApiLogQuery()
                ->filterByRequestType($apiLogRequestType)
                ->filterByResultCode_In($resultCodes)
            ->endUse()
            ->findOne();

        $paymentCrefoPayOrderItemToCrefoPayApiLogTransfer = new PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer();

        if ($paymentCrefoPayOrderItemToCrefoPayApiLogEntity === null) {
            return $paymentCrefoPayOrderItemToCrefoPayApiLogTransfer;
        }

        return $this->getMapper()
            ->mapEntityToPaymentCrefoPayOrderItemToCrefoPayApiLogTransfer(
                $paymentCrefoPayOrderItemToCrefoPayApiLogEntity,
                $paymentCrefoPayOrderItemToCrefoPayApiLogTransfer
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
        $paymentCrefoPayOrderItemToCrefoPayNotificationEntity = $this->getPaymentCrefoPayOrderItemToCrefoPayNotificationQuery()
            ->useSpyPaymentCrefoPayOrderItemQuery()
                ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->endUse()
            ->useSpyPaymentCrefoPayNotificationQuery()
                ->filterByTransactionStatus($notificationTransactionStatus)
            ->endUse()
            ->findOne();

        $paymentCrefoPayOrderItemToCrefoPayNotificationTransfer = new PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer();

        if ($paymentCrefoPayOrderItemToCrefoPayNotificationEntity === null) {
            return $paymentCrefoPayOrderItemToCrefoPayNotificationTransfer;
        }

        return $this->getMapper()
            ->mapEntityToPaymentCrefoPayOrderItemToCrefoPayNotificationTransfer(
                $paymentCrefoPayOrderItemToCrefoPayNotificationEntity,
                $paymentCrefoPayOrderItemToCrefoPayNotificationTransfer
            );
    }

    /**
     * @param int $idSalesOrderItem
     * @param string $notificationOrderStatus
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer
     */
    public function findPaymentCrefoPayOrderItemToCrefoPayNotificationByIdSalesOrderItemAndOrderStatus(
        int $idSalesOrderItem,
        string $notificationOrderStatus
    ): PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer {
        $paymentCrefoPayOrderItemToCrefoPayNotificationEntity = $this->getPaymentCrefoPayOrderItemToCrefoPayNotificationQuery()
            ->useSpyPaymentCrefoPayOrderItemQuery()
                ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->endUse()
            ->useSpyPaymentCrefoPayNotificationQuery()
                ->filterByOrderStatus($notificationOrderStatus)
            ->endUse()
            ->findOne();

        $paymentCrefoPayOrderItemToCrefoPayNotificationTransfer = new PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer();

        if ($paymentCrefoPayOrderItemToCrefoPayNotificationEntity === null) {
            return $paymentCrefoPayOrderItemToCrefoPayNotificationTransfer;
        }

        return $this->getMapper()
            ->mapEntityToPaymentCrefoPayOrderItemToCrefoPayNotificationTransfer(
                $paymentCrefoPayOrderItemToCrefoPayNotificationEntity,
                $paymentCrefoPayOrderItemToCrefoPayNotificationTransfer
            );
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItem[] $paymentCrefoPayOrderItemEntities
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer $crefoPayOrderItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    protected function mapOrderItemEntitiesToOrderItemCollection(
        ObjectCollection $paymentCrefoPayOrderItemEntities,
        PaymentCrefoPayOrderItemCollectionTransfer $crefoPayOrderItemCollectionTransfer
    ): PaymentCrefoPayOrderItemCollectionTransfer {
        $mapper = $this->getMapper();
        $result = new ArrayObject();

        foreach ($paymentCrefoPayOrderItemEntities as $paymentCrefoPayOrderItemEntity) {
            $paymentCrefoPayOrderItemTransfer = $mapper->mapEntityToPaymentCrefoPayOrderItemTransfer(
                $paymentCrefoPayOrderItemEntity,
                new PaymentCrefoPayOrderItemTransfer()
            );
            $result->append($paymentCrefoPayOrderItemTransfer);
        }

        return $crefoPayOrderItemCollectionTransfer
            ->setCrefoPayOrderItems($result);
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

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected function getSalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return $this->getFactory()->createSpySalesOrderItemQuery();
    }
}
