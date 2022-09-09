<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Service\CrefoPay\Generator;

use Generated\Shared\Transfer\QuoteTransfer;

interface CrefoPayUserIdGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    public function generateUserId(QuoteTransfer $quoteTransfer): string;
}
