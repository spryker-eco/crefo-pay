<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Hook\Checkout\Saver;

use Generated\Shared\Transfer\CrefoPayApiRequestTransfer;
use Generated\Shared\Transfer\CrefoPayApiResponseTransfer;
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

        $paymentCrefoPayTransfer = $this->getPaymentCrefoPayTransfer($requestTransfer, $responseTransfer);

        $paymentCrefoPayOrderItemCollectionTransfer = $this->reader
            ->findAllPaymentCrefoPayOrderItemsByCrefoPayOrderId(
                $requestTransfer->getReserveRequest()->getOrderID()
            );

        $this->writer->updatePaymentEntities(
            $this->config->getOmsStatusReserved(),
            $paymentCrefoPayOrderItemCollectionTransfer,
            $paymentCrefoPayTransfer
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
        if ($responseTransfer->getReserveResponse()->getAdditionalData() === null) {
            return null;
        }

        $paymentCrefoPayTransfer = $this->reader
            ->findPaymentCrefoPayByCrefoPayOrderId(
                $requestTransfer->getReserveRequest()->getOrderID()
            );

        $paymentCrefoPayTransfer->setAdditionalData(
            $responseTransfer->getReserveResponse()->getAdditionalData()->serialize()
        );

        return $paymentCrefoPayTransfer;
    }
}
