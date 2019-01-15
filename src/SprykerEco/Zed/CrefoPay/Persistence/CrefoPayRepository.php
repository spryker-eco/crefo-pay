<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemQuery;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \SprykerEco\Zed\CrefoPay\Persistence\CrefoPayPersistenceFactory getFactory()
 */
class CrefoPayRepository extends AbstractRepository implements CrefoPayRepositoryInterface
{
    /**
     * @param string $crefoPayOrderId
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
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
    public function findAllPaymentCrefoPayOrderItemsByCrefoPayOrderId(string $crefoPayOrderId): PaymentCrefoPayOrderItemCollectionTransfer
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
