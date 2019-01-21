<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Form\DataProvider;

use Generated\Shared\Transfer\CrefoPayPaymentTransfer;
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
        $quoteTransfer = parent::getData($quoteTransfer);
        if ($quoteTransfer->getPayment()->getCrefoPaySofort() !== null) {
            return $quoteTransfer;
        }

        $quoteTransfer->getPayment()->setCrefoPaySofort(new CrefoPayPaymentTransfer());

        return $quoteTransfer;
    }
}
