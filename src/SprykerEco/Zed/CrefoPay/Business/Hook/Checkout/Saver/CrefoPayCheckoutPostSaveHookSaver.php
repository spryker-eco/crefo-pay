<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Hook\Checkout\Saver;

use ArrayObject;
use Generated\Shared\Transfer\CrefoPayApiRequestTransfer;
use Generated\Shared\Transfer\CrefoPayApiResponseTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;
use SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface;
use SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;

class CrefoPayCheckoutPostSaveHookSaver implements CrefoPayCheckoutHookSaverInterface
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
     * @param \Generated\Shared\Transfer\CrefoPayApiRequestTransfer $requestTransfer
     * @param \Generated\Shared\Transfer\CrefoPayApiResponseTransfer $responseTransfer
     *
     * @return void
     */
    public function savePaymentEntities(
        CrefoPayApiRequestTransfer $requestTransfer,
        CrefoPayApiResponseTransfer $responseTransfer
    ): void {
        if (!$responseTransfer->getIsSuccess()) {
            return;
        }

        $this->writer->updatePaymentEntities(
            $this->getPaymentCrefoPayOrderItemCollectionTransfer($requestTransfer),
            $this->getPaymentCrefoPayTransfer($requestTransfer, $responseTransfer),
            $responseTransfer->getCrefoPayApiLogId()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayApiRequestTransfer $requestTransfer
     * @param \Generated\Shared\Transfer\CrefoPayApiResponseTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer|null
     */
    protected function getPaymentCrefoPayTransfer(
        CrefoPayApiRequestTransfer $requestTransfer,
        CrefoPayApiResponseTransfer $responseTransfer
    ): ?PaymentCrefoPayTransfer {
        $paymentCrefoPayTransfer = $this->reader
            ->getPaymentCrefoPayByCrefoPayOrderId(
                $requestTransfer->getReserveRequest()->getOrderID()
            );

        $paymentCrefoPayTransfer->setAuthorizedAmount(
            $requestTransfer->getReserveRequest()->getAmount()->getAmount()
        );

        if ($responseTransfer->getReserveResponse()->getAdditionalData() !== null) {
            $paymentCrefoPayTransfer->setAdditionalData(
                $responseTransfer->getReserveResponse()->getAdditionalData()->serialize()
            );
        }

        return $paymentCrefoPayTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayApiRequestTransfer $requestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    protected function getPaymentCrefoPayOrderItemCollectionTransfer(CrefoPayApiRequestTransfer $requestTransfer): PaymentCrefoPayOrderItemCollectionTransfer
    {
        $paymentCrefoPayOrderItemCollection = $this->reader
            ->getPaymentCrefoPayOrderItemCollectionByCrefoPayOrderId(
                $requestTransfer->getReserveRequest()->getOrderID()
            );

        return $this->setOrderItemsStatus($paymentCrefoPayOrderItemCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer $paymentCrefoPayOrderItemCollection
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    protected function setOrderItemsStatus(
        PaymentCrefoPayOrderItemCollectionTransfer $paymentCrefoPayOrderItemCollection
    ): PaymentCrefoPayOrderItemCollectionTransfer {
        $status = $this->config->getOmsStatusReserved();
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
