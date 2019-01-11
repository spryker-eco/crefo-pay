<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Writer;

use Generated\Shared\Transfer\CrefoPayApiRequestTransfer;
use Generated\Shared\Transfer\CrefoPayApiResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayApiLogTransfer;
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
    public function savePaymentEntities(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
        $paymentCrefoPayTransfer = $this->savePaymentCrefoPay($quoteTransfer, $saveOrderTransfer);

        foreach ($saveOrderTransfer->getOrderItems() as $orderItem) {
            $this->savePaymentCrefoPayOrderItem($paymentCrefoPayTransfer, $orderItem);
        }
    }

    /**
     * @param string $status
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer[] $paymentCrefoPayOrderItemTransfers
     * @param \Generated\Shared\Transfer\PaymentCrefoPayTransfer|null $paymentCrefoPayTransfer
     *
     * @return void
     */
    public function updatePaymentEntities(
        string $status,
        array $paymentCrefoPayOrderItemTransfers,
        ?PaymentCrefoPayTransfer $paymentCrefoPayTransfer = null
    ): void {
        // TODO: Implement updatePaymentEntities() method.
    }

    /**
     * @param string $type
     * @param \Generated\Shared\Transfer\CrefoPayApiRequestTransfer $request
     * @param \Generated\Shared\Transfer\CrefoPayApiResponseTransfer $response
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayApiLogTransfer
     */
    public function saveApiLog(
        string $type,
        CrefoPayApiRequestTransfer $request,
        CrefoPayApiResponseTransfer $response
    ): PaymentCrefoPayApiLogTransfer {
        // TODO: Implement saveApiLog() method.
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    protected function savePaymentCrefoPay(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): PaymentCrefoPayTransfer {
        $paymentCrefoPayTransfer = (new PaymentCrefoPayTransfer())
            ->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setCrefoPayOrderId($quoteTransfer->getCrefoPayTransaction()->getCrefoPayOrderId())
            ->setPaymentMethod($quoteTransfer->getPayment()->getPaymentSelection())
            ->setClientIp($quoteTransfer->getCrefoPayTransaction()->getClientIp());

        return $this->entityManager->savePaymentCrefoPay($paymentCrefoPayTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayTransfer $paymentCrefoPayTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $orderItem
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer
     */
    protected function savePaymentCrefoPayOrderItem(
        PaymentCrefoPayTransfer $paymentCrefoPayTransfer,
        ItemTransfer $orderItem
    ): PaymentCrefoPayOrderItemTransfer {
        $paymentCrefoPayOrderItemTransfer = (new PaymentCrefoPayOrderItemTransfer())
            ->setFkSalesOrderItem($orderItem->getIdSalesOrderItem())
            ->setFkPaymentCrefoPay($paymentCrefoPayTransfer->getIdPaymentCrefoPay())
            ->setStatus($this->config->getOmsStatusNew());

        return $this->entityManager->savePaymentCrefoPayOrderItem($paymentCrefoPayOrderItemTransfer);
    }
}
