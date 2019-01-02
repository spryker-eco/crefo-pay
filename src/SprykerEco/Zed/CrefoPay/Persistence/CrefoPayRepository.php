<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Persistence;

use Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemQuery;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \SprykerEco\Zed\CrefoPay\Persistence\CrefoPayPersistenceFactory getFactory()
 */
class CrefoPayRepository extends AbstractRepository implements CrefoPayRepositoryInterface
{
    /**
     * @param string $reference
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function getPaymentCrefoPayByReference(string $reference): PaymentCrefoPayTransfer
    {
        $query = $this->getPaymentCrefoPayQuery()->filterByReference($reference);
        $entityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        if ($entityTransfer === null) {
            return new PaymentCrefoPayTransfer();
        }

        return $this->getFactory()
            ->createCrefoPayPersistenceMapper()
            ->mapEntityTransferToPaymentCrefoPayTransfer($entityTransfer, new PaymentCrefoPayTransfer());
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function getPaymentCrefoPayByIdSalesOrder(int $idSalesOrder): PaymentCrefoPayTransfer
    {
        $query = $this->getPaymentCrefoPayQuery()->filterByFkSalesOrder($idSalesOrder);
        $entityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        if ($entityTransfer === null) {
            return new PaymentCrefoPayTransfer();
        }

        return $this->getFactory()
            ->createCrefoPayPersistenceMapper()
            ->mapEntityTransferToPaymentCrefoPayTransfer($entityTransfer, new PaymentCrefoPayTransfer());
    }

    /**
     * @param string $pspReference
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function getPaymentCrefoPayByPspReference(string $pspReference): PaymentCrefoPayTransfer
    {
        $query = $this->getPaymentCrefoPayQuery()->filterByPspReference($pspReference);
        $entityTransfer = $this->buildQueryFromCriteria($query)->findOne();

        if ($entityTransfer === null) {
            return new PaymentCrefoPayTransfer();
        }

        return $this->getFactory()
            ->createCrefoPayPersistenceMapper()
            ->mapEntityTransferToPaymentCrefoPayTransfer($entityTransfer, new PaymentCrefoPayTransfer());
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer[]
     */
    public function getAllPaymentCrefoPayOrderItemsByIdSalesOrder(int $idSalesOrder): array
    {
        $query = $this->getPaymentCrefoPayOrderItemQuery()
            ->useSpyPaymentCrefoPayQuery()
                ->filterByFkSalesOrder($idSalesOrder)
            ->endUse();

        $entityTransfers = $this->buildQueryFromCriteria($query)->find();

        $mapper = $this->getFactory()->createCrefoPayPersistenceMapper();
        $result = [];

        foreach ($entityTransfers as $entityTransfer) {
            $result[] = $mapper
                ->mapEntityTransferToPaymentCrefoPayOrderItemTransfer(
                    $entityTransfer,
                    new PaymentCrefoPayOrderItemTransfer()
                );
        }

        return $result;
    }

    /**
     * @param int[] $orderItemIds
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer[]
     */
    public function getOrderItemsByIdsSalesOrderItems(array $orderItemIds): array
    {
        $query = $this->getPaymentCrefoPayOrderItemQuery()->filterByFkSalesOrderItem_In($orderItemIds);
        $entityTransfers = $this->buildQueryFromCriteria($query)->find();

        $mapper = $this->getFactory()->createCrefoPayPersistenceMapper();
        $result = [];

        foreach ($entityTransfers as $entityTransfer) {
            $result[] = $mapper
                ->mapEntityTransferToPaymentCrefoPayOrderItemTransfer(
                    $entityTransfer,
                    new PaymentCrefoPayOrderItemTransfer()
                );
        }

        return $result;
    }

    /**
     * @param int $idSalesOrder
     * @param int[] $orderItemIds
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer[]
     */
    public function getRemainingPaymentCrefoPayOrderItems(int $idSalesOrder, array $orderItemIds): array
    {
        $query = $this->getPaymentCrefoPayOrderItemQuery()
            ->filterByFkSalesOrderItem($orderItemIds, Criteria::NOT_IN)
            ->useSpyPaymentCrefoPayQuery()
                ->filterByFkSalesOrder($idSalesOrder)
            ->endUse();

        $entityTransfers = $this->buildQueryFromCriteria($query)->find();

        $mapper = $this->getFactory()->createCrefoPayPersistenceMapper();
        $result = [];

        foreach ($entityTransfers as $entityTransfer) {
            $result[] = $mapper
                ->mapEntityTransferToPaymentCrefoPayOrderItemTransfer(
                    $entityTransfer,
                    new PaymentCrefoPayOrderItemTransfer()
                );
        }

        return $result;
    }

    /**
     * @param int $idSalesOrder
     * @param int[] $orderItemIds
     *
     * @return int[]
     */
    public function getRemainingSalesOrderItemIds(int $idSalesOrder, array $orderItemIds): array
    {
        $query = $this->getSalesOrderItemQuery()
            ->filterByIdSalesOrderItem($orderItemIds, Criteria::NOT_IN)
            ->useOrderQuery()
                ->filterByIdSalesOrder($idSalesOrder)
            ->endUse();

        $entityTransfers = $this->buildQueryFromCriteria($query)->find();

        $remainingOrderItemIds = array_map(
            function (SpySalesOrderItemEntityTransfer $entityTransfer) {
                return $entityTransfer->getIdSalesOrderItem();
            },
            $entityTransfers
        );

        return $remainingOrderItemIds;
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
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected function getSalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return $this->getFactory()->createSpySalesOrderItemQuery();
    }
}
