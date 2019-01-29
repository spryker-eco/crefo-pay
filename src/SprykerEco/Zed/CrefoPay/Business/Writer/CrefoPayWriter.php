<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Writer;

use Generated\Shared\Transfer\CrefoPayNotificationTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayNotificationTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;
use SprykerEco\Zed\CrefoPay\Persistence\CrefoPayEntityManagerInterface;

class CrefoPayWriter implements CrefoPayWriterInterface
{
    use TransactionTrait;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Persistence\CrefoPayEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \SprykerEco\Zed\CrefoPay\CrefoPayConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Persistence\CrefoPayEntityManagerInterface $entityManager
     * @param \SprykerEco\Zed\CrefoPay\CrefoPayConfig $config
     */
    public function __construct(
        CrefoPayEntityManagerInterface $entityManager,
        CrefoPayConfig $config
    ) {
        $this->entityManager = $entityManager;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function createPaymentEntities(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
        $paymentCrefoPayTransfer = $this->createPaymentCrefoPayEntity($quoteTransfer, $saveOrderTransfer);

        foreach ($saveOrderTransfer->getOrderItems() as $orderItem) {
            $this->createPaymentCrefoPayOrderItemEntity($paymentCrefoPayTransfer, $orderItem);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer $paymentCrefoPayOrderItemCollection
     * @param \Generated\Shared\Transfer\PaymentCrefoPayTransfer|null $paymentCrefoPayTransfer
     *
     * @param int|null $crefoPayApiLogId
     *
     * @return void
     */
    public function updatePaymentEntities(
        PaymentCrefoPayOrderItemCollectionTransfer $paymentCrefoPayOrderItemCollection,
        ?PaymentCrefoPayTransfer $paymentCrefoPayTransfer = null,
        ?int $crefoPayApiLogId = null
    ): void {
        $this->getTransactionHandler()->handleTransaction(
            function () use ($paymentCrefoPayOrderItemCollection, $paymentCrefoPayTransfer, $crefoPayApiLogId) {
                if ($paymentCrefoPayTransfer !== null) {
                    $this->entityManager->savePaymentCrefoPayEntity($paymentCrefoPayTransfer);
                }

                foreach ($paymentCrefoPayOrderItemCollection->getCrefoPayOrderItems() as $paymentCrefoPayOrderItemTransfer) {
                    $paymentCrefoPayOrderItemTransfer = $this->entityManager
                        ->savePaymentCrefoPayOrderItemEntity($paymentCrefoPayOrderItemTransfer);
                    if ($crefoPayApiLogId !== null) {
                        $this->createPaymentCrefoPayOrderItemToCrefoPayApiLogEntity(
                            $paymentCrefoPayOrderItemTransfer->getIdPaymentCrefoPayOrderItem(),
                            $crefoPayApiLogId
                        );
                    }
                }
            }
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayNotificationTransfer $notificationTransfer
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer $paymentCrefoPayOrderItemCollection
     *
     * @return void
     */
    public function createNotificationEntities(
        CrefoPayNotificationTransfer $notificationTransfer,
        PaymentCrefoPayOrderItemCollectionTransfer $paymentCrefoPayOrderItemCollection
    ): void {
        $this->getTransactionHandler()->handleTransaction(
            function () use ($notificationTransfer, $paymentCrefoPayOrderItemCollection) {
                $paymentCrefoPayNotificationTransfer = $this->createPaymentCrefoPayNotificationEntity($notificationTransfer);
                foreach ($paymentCrefoPayOrderItemCollection->getCrefoPayOrderItems() as $paymentCrefoPayOrderItemTransfer) {
                    $this->entityManager->savePaymentCrefoPayOrderItemEntity($paymentCrefoPayOrderItemTransfer);
                    $this->createPaymentCrefoPayOrderItemToCrefoPayNotificationEntity(
                        $paymentCrefoPayOrderItemTransfer->getIdPaymentCrefoPayOrderItem(),
                        $paymentCrefoPayNotificationTransfer->getIdPaymentCrefoPayNotification()
                    );
                }
            }
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    protected function createPaymentCrefoPayEntity(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): PaymentCrefoPayTransfer {
        $paymentCrefoPayTransfer = (new PaymentCrefoPayTransfer())
            ->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setCrefoPayOrderId($quoteTransfer->getCrefoPayTransaction()->getCrefoPayOrderId())
            ->setPaymentMethod($quoteTransfer->getPayment()->getPaymentSelection())
            ->setClientIp($quoteTransfer->getCrefoPayTransaction()->getClientIp());

        return $this->entityManager->savePaymentCrefoPayEntity($paymentCrefoPayTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayTransfer $paymentCrefoPayTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $orderItem
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer
     */
    protected function createPaymentCrefoPayOrderItemEntity(
        PaymentCrefoPayTransfer $paymentCrefoPayTransfer,
        ItemTransfer $orderItem
    ): PaymentCrefoPayOrderItemTransfer {
        $paymentCrefoPayOrderItemTransfer = (new PaymentCrefoPayOrderItemTransfer())
            ->setFkSalesOrderItem($orderItem->getIdSalesOrderItem())
            ->setFkPaymentCrefoPay($paymentCrefoPayTransfer->getIdPaymentCrefoPay())
            ->setStatus($this->config->getOmsStatusNew());

        return $this->entityManager->savePaymentCrefoPayOrderItemEntity($paymentCrefoPayOrderItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayNotificationTransfer $notificationTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayNotificationTransfer
     */
    protected function createPaymentCrefoPayNotificationEntity(CrefoPayNotificationTransfer $notificationTransfer): PaymentCrefoPayNotificationTransfer
    {
        $paymentCrefoPayNotificationTransfer = (new PaymentCrefoPayNotificationTransfer())
            ->setCrefoPayOrderId($notificationTransfer->getOrderID())
            ->setCaptureId($notificationTransfer->getCaptureID())
            ->setMerchantReference($notificationTransfer->getMerchantReference())
            ->setPaymentReference($notificationTransfer->getPaymentReference())
            ->setUserId($notificationTransfer->getUserID())
            ->setAmount($notificationTransfer->getAmount())
            ->setCurrency($notificationTransfer->getCurrency())
            ->setTransactionStatus($notificationTransfer->getTransactionStatus())
            ->setOrderStatus($notificationTransfer->getOrderStatus())
            ->setTimestamp($notificationTransfer->getTimestamp())
            ->setVersion($notificationTransfer->getVersion());

        return $this->entityManager->savePaymentCrefoPayNotificationEntity($paymentCrefoPayNotificationTransfer);
    }

    /**
     * @param int $idPaymentCrefoPayOrderItem
     * @param int $idPaymentCrefoPayApiLog
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer
     */
    protected function createPaymentCrefoPayOrderItemToCrefoPayApiLogEntity(
        int $idPaymentCrefoPayOrderItem,
        int $idPaymentCrefoPayApiLog
    ): PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer {
        $paymentCrefoPayOrderItemToCrefoPayApiLogTransfer = (new PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer())
            ->setFkPaymentCrefoPayOrderItem($idPaymentCrefoPayOrderItem)
            ->setFkPaymentCrefoPayApiLog($idPaymentCrefoPayApiLog);

        return $this->entityManager
            ->savePaymentCrefoPayOrderItemToCrefoPayApiLogEntity($paymentCrefoPayOrderItemToCrefoPayApiLogTransfer);
    }

    /**
     * @param int $idPaymentCrefoPayOrderItem
     * @param int $idPaymentCrefoPayNotification
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer
     */
    protected function createPaymentCrefoPayOrderItemToCrefoPayNotificationEntity(
        int $idPaymentCrefoPayOrderItem,
        int $idPaymentCrefoPayNotification
    ): PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer {
        $paymentCrefoPayOrderItemToCrefoPayNotificationTransfer = (new PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer())
            ->setFkPaymentCrefoPayOrderItem($idPaymentCrefoPayOrderItem)
            ->setFkPaymentCrefoPayNotification($idPaymentCrefoPayNotification);

        return $this->entityManager
            ->savePaymentCrefoPayOrderItemToCrefoPayNotificationEntity($paymentCrefoPayOrderItemToCrefoPayNotificationTransfer);
    }
}
