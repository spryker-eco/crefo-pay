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
use SprykerEco\Zed\CrefoPay\Persistence\Mapper\CrefoPayPersistenceMapperInterface;

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
     * @param string|null $captureId
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function findPaymentCrefoPayOrderItemsByCrefoPayOrderIdAndCaptureId(
        string $crefoPayOrderId,
        ?string $captureId = null
    ): PaymentCrefoPayOrderItemCollectionTransfer {
        $query = $this->getPaymentCrefoPayOrderItemQuery();
        $query->useSpyPaymentCrefoPayQuery()
                ->filterByCrefoPayOrderId($crefoPayOrderId)
            ->endUse();

        if ($captureId !== null) {
            $query->filterByCaptureId($captureId);
        }

        $paymentCrefoPayOrderItemEntities = $query->find();

        $mapper = $this->getMapper();
        $result = new ArrayObject();

        foreach ($paymentCrefoPayOrderItemEntities as $paymentCrefoPayOrderItemEntity) {
            $paymentCrefoPayOrderItemTransfer = $mapper->mapEntityToPaymentCrefoPayOrderItemTransfer(
                $paymentCrefoPayOrderItemEntity,
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
        $paymentCrefoPayOrderItemEntities = $this->getPaymentCrefoPayOrderItemQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds)
            ->find();

        $mapper = $this->getMapper();
        $result = new ArrayObject();

        foreach ($paymentCrefoPayOrderItemEntities as $paymentCrefoPayOrderItemEntity) {
            $paymentCrefoPayOrderItemTransfer = $mapper->mapEntityToPaymentCrefoPayOrderItemTransfer(
                $paymentCrefoPayOrderItemEntity,
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
     * @return \SprykerEco\Zed\CrefoPay\Persistence\Mapper\CrefoPayPersistenceMapperInterface
     */
    protected function getMapper(): CrefoPayPersistenceMapperInterface
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
