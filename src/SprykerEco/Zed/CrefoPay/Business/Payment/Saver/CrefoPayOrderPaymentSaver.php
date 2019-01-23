<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Payment\Saver;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use SprykerEco\Shared\CrefoPay\CrefoPayConfig;
use SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface;

class CrefoPayOrderPaymentSaver implements CrefoPayOrderPaymentSaverInterface
{
    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface
     */
    protected $writer;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface $writer
     */
    public function __construct(CrefoPayWriterInterface $writer)
    {
        $this->writer = $writer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        if ($quoteTransfer->getPayment()->getPaymentProvider() !== CrefoPayConfig::PROVIDER_NAME) {
            return;
        }

        $this->writer->createPaymentEntities($quoteTransfer, $saveOrderTransfer);
    }
}
