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

class CancelOmsCommandSaver implements CrefoPayOmsCommandSaverInterface
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
     * @param \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface $reader
     * @param \SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface $writer
     * @param \SprykerEco\Zed\CrefoPay\CrefoPayConfig $config
     */
    public function __construct(
        CrefoPayReaderInterface $reader,
        CrefoPayWriterInterface $writer,
        CrefoPayConfig $config
    ) {
        $this->reader = $reader;
        $this->writer = $writer;
        $this->config = $config;
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
            null,
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
        $status = $this->config->getOmsStatusCancellationPending();
        $paymentCrefoPayOrderItemCollection = $this->reader
            ->findPaymentCrefoPayOrderItemsByCrefoPayOrderId(
                $crefoPayOmsCommandTransfer->getPaymentCrefoPay()->getCrefoPayOrderId()
            );
        $paymentCrefoPayOrderItems = array_map(
            function (PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer) use ($status) {
                return $paymentCrefoPayOrderItemTransfer->setStatus($status);
            },
            $paymentCrefoPayOrderItemCollection->getCrefoPayOrderItems()->getArrayCopy()
        );

        return $paymentCrefoPayOrderItemCollection
            ->setCrefoPayOrderItems(new ArrayObject($paymentCrefoPayOrderItems));
    }
}
