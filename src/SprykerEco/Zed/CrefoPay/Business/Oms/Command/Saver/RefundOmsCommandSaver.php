<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver;

use ArrayObject;
use Generated\Shared\Transfer\CrefoPayOmsCommandTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;
use SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface;
use SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToRefundFacadeInterface;

class RefundOmsCommandSaver implements CrefoPayOmsCommandSaverInterface
{
    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface
     */
    protected $reader;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface
     */
    protected $writer;

    /**
     * @var \SprykerEco\Zed\CrefoPay\CrefoPayConfig
     */
    protected $config;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToRefundFacadeInterface
     */
    protected $refundFacade;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface $reader
     * @param \SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface $writer
     * @param \SprykerEco\Zed\CrefoPay\CrefoPayConfig $config
     * @param \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToRefundFacadeInterface $refundFacade
     */
    public function __construct(
        CrefoPayReaderInterface $reader,
        CrefoPayWriterInterface $writer,
        CrefoPayConfig $config,
        CrefoPayToRefundFacadeInterface $refundFacade
    ) {
        $this->reader = $reader;
        $this->writer = $writer;
        $this->config = $config;
        $this->refundFacade = $refundFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return void
     */
    public function savePaymentEntities(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): void
    {
        if ($crefoPayOmsCommandTransfer->getResponse()->getIsSuccess() === false) {
            return;
        }

        $paymentCrefoPayOrderItemsCollection = $this->addOmsStatusToOrderItems(
            $crefoPayOmsCommandTransfer->getPaymentCrefoPayOrderItemCollection()
        );

        $this->writer->updatePaymentEntities(
            $paymentCrefoPayOrderItemsCollection,
            $this->getPaymentCrefoPayTransfer($crefoPayOmsCommandTransfer),
            $crefoPayOmsCommandTransfer->getResponse()->getCrefoPayApiLogId()
        );

        if ($crefoPayOmsCommandTransfer->getRefund()->getAmount() > 0) {
            $this->refundFacade->saveRefund($crefoPayOmsCommandTransfer->getRefund());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer $paymentCrefoPayOrderItemCollection
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    protected function addOmsStatusToOrderItems(
        PaymentCrefoPayOrderItemCollectionTransfer $paymentCrefoPayOrderItemCollection
    ): PaymentCrefoPayOrderItemCollectionTransfer {
        $status = $this->config->getOmsStatusRefunded();

        $paymentCrefoPayOrderItems = array_map(
            function (PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer) use ($status) {
                return $paymentCrefoPayOrderItemTransfer->setStatus($status);
            },
            $paymentCrefoPayOrderItemCollection->getCrefoPayOrderItems()->getArrayCopy()
        );

        return $paymentCrefoPayOrderItemCollection
            ->setCrefoPayOrderItems(new ArrayObject($paymentCrefoPayOrderItems));
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    protected function getPaymentCrefoPayTransfer(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): PaymentCrefoPayTransfer
    {
        $paymentCrefoPayTransfer = $crefoPayOmsCommandTransfer->getPaymentCrefoPay();
        $refundedAmount = $paymentCrefoPayTransfer->getRefundedAmount();
        $requestedToRefundAmount = $crefoPayOmsCommandTransfer
            ->getRequest()
            ->getRefundRequest()
            ->getAmount()
            ->getAmount();

        if ($crefoPayOmsCommandTransfer->getExpensesResponse() && $crefoPayOmsCommandTransfer->getExpensesResponse()->getIsSuccess()) {
            $expensesRefundAmount = $crefoPayOmsCommandTransfer
                ->getExpensesRequest()
                ->getRefundRequest()
                ->getAmount()
                ->getAmount();

            $requestedToRefundAmount += $expensesRefundAmount;

            $paymentCrefoPayTransfer->setExpensesRefundedAmount($expensesRefundAmount);
        }

        return $paymentCrefoPayTransfer
            ->setRefundedAmount($refundedAmount + $requestedToRefundAmount);
    }
}
