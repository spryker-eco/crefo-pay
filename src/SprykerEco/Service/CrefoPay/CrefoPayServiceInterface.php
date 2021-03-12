<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Service\CrefoPay;

use Generated\Shared\Transfer\QuoteTransfer;

interface CrefoPayServiceInterface
{
    /**
     * Specification:
     *  - Generate unique identifier for order id in CrefoPay system.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param bool $isIndependentGenerations
     *
     * @return string
     */
    public function generateCrefoPayOrderId(QuoteTransfer $quoteTransfer, bool $isIndependentGenerations): string;
}
