<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver;

use ArrayObject;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer;
use Generated\Shared\Transfer\CrefoPayOmsCommandTransfer;
use SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface;
use SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToOmsFacadeInterface;

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
     * @var \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToOmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface $reader
     * @param \SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface $writer
     * @param \SprykerEco\Zed\CrefoPay\CrefoPayConfig $config
     * @param \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToOmsFacadeInterface $omsFacade
     */
    public function __construct(

        CrefoPayReaderInterface $reader,
        CrefoPayWriterInterface $writer,
        CrefoPayConfig $config,
        CrefoPayToOmsFacadeInterface $omsFacade
    ) {
        $this->reader = $reader;
        $this->writer = $writer;
        $this->config = $config;
        $this->omsFacade = $omsFacade;
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

        $this->writer->updatePaymentEntities(
            $this->getPaymentCrefoPayOrderItemCollection($crefoPayOmsCommandTransfer),
            $this->getPaymentCrefoPay($crefoPayOmsCommandTransfer),
            $crefoPayOmsCommandTransfer->getResponse()->getCrefoPayApiLogId()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    protected function getPaymentCrefoPayOrderItemCollection(
        CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
    ): PaymentCrefoPayOrderItemCollectionTransfer {

        $status = $this->statusMapper
            ->mapNotificationOrderStatusToOmsStatus(
                $crefoPayOmsCommandTransfer->getResponse()->getCaptureResponse()->getStatus()
            );
        $captureId = $crefoPayOmsCommandTransfer->getRequest()->getCaptureRequest()->getCaptureID();
        $paymentCrefoPayOrderItemCollection = $this->reader
            ->findPaymentCrefoPayOrderItemsByCrefoPayToSalesOrderItemsCollection(
                $crefoPayOmsCommandTransfer->getCrefoPayToSalesOrderItemsCollection()
            );
        $paymentCrefoPayOrderItems = array_map(
            function (PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer) use ($status, $captureId) {
                return $paymentCrefoPayOrderItemTransfer
                    ->setStatus($status)
                    ->setCaptureId($captureId);
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
    protected function getPaymentCrefoPay(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): PaymentCrefoPayTransfer
    {
        $paymentCrefoPayTransfer = $crefoPayOmsCommandTransfer->getPaymentCrefoPay();
        $capturedAmount = $paymentCrefoPayTransfer->getCapturedAmount();
        $requestedAmount = $crefoPayOmsCommandTransfer->getRequest()->getCaptureRequest()->getAmount()->getAmount();

        return $paymentCrefoPayTransfer
            ->setCapturedAmount($capturedAmount + $requestedAmount);
    }
}
