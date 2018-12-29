<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Form\DataProvider;

use Generated\Shared\Transfer\CrefoPaySofortPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class SofortFormDataProvider extends AbstractFormDataProvider
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer = $this->updateQuoteWithPaymentData($quoteTransfer);

        if ($quoteTransfer->getPayment()->getCrefoPaySofort() === null) {
            $quoteTransfer->getPayment()->setCrefoPaySofort(new CrefoPaySofortPaymentTransfer());
        }

        $this->quoteClient->setQuote($quoteTransfer);

        return $quoteTransfer;
    }
}
