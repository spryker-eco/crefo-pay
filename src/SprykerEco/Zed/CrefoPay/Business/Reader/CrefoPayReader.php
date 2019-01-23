<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Reader;

use Generated\Shared\Transfer\CrefoPayToSalesOrderItemCollectionTransfer;
use Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer;
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
     * @param string $crefoPayOrderId
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function findPaymentCrefoPayOrderItemsByCrefoPayOrderId(string $crefoPayOrderId): PaymentCrefoPayOrderItemCollectionTransfer
    {
        return $this->repository->findPaymentCrefoPayOrderItemsByCrefoPayOrderId($crefoPayOrderId);
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemCollectionTransfer $crefoPayToSalesOrderItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function findPaymentCrefoPayOrderItemsByCrefoPayToSalesOrderItemCollection(
        CrefoPayToSalesOrderItemCollectionTransfer $crefoPayToSalesOrderItemCollectionTransfer
    ): PaymentCrefoPayOrderItemCollectionTransfer {
        $salesOrderItemIds = array_map(
            function (CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer) {
                return $crefoPayToSalesOrderItemTransfer->getIdSalesOrderItem();
            },
            $crefoPayToSalesOrderItemCollectionTransfer->getCrefoPayToSalesOrderItems()->getArrayCopy()
        );

        return $this->repository->findPaymentCrefoPayOrderItemsBySalesOrderItemIds($salesOrderItemIds);
    }
}
