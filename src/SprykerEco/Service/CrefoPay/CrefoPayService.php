<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Service\CrefoPay;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \SprykerEco\Service\CrefoPay\CrefoPayServiceFactory getFactory()
 */
class CrefoPayService extends AbstractService implements CrefoPayServiceInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    public function generateCrefoPayOrderId(QuoteTransfer $quoteTransfer): string
    {
        return $this->getFactory()
            ->createUniqueIdGenerator()
            ->generateCrefoPayOrderId($quoteTransfer);
    }
}
