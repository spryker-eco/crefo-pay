<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\CrefoPay;

use Generated\Shared\Transfer\QuoteTransfer;

interface CrefoPayClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function startCrefoPayTransaction(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
