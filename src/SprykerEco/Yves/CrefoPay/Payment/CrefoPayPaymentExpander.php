<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Payment;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\CrefoPay\CrefoPayConfig;
use Symfony\Component\HttpFoundation\Request;

class CrefoPayPaymentExpander implements CrefoPayPaymentExpanderInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(Request $request, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer
            ->getPayment()
            ->setPaymentProvider(CrefoPayConfig::PROVIDER_NAME)
            ->setPaymentMethod($quoteTransfer->getPayment()->getPaymentSelection());

        return $quoteTransfer;
    }
}
