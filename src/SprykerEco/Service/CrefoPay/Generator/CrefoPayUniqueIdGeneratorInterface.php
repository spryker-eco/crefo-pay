<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Service\CrefoPay\Generator;

use Generated\Shared\Transfer\QuoteTransfer;

interface CrefoPayUniqueIdGeneratorInterface
{
    /**
     * @deprecated Use {@link generateCrefoPayOrderIdIndependent()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    public function generateCrefoPayOrderId(QuoteTransfer $quoteTransfer): string;

    /**
     * @return string
     */
    public function generateCrefoPayOrderIdIndependent(): string;
}
