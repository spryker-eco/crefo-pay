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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    public function generateCrefoPayOrderId(QuoteTransfer $quoteTransfer): string
    {
        /** @var \SprykerEco\Service\CrefoPay\Generator\CrefoPayUniqueIdGeneratorInterface $generator */
        $generator = $this->getFactory()
            ->createUniqueIdGenerator();

        return $generator->generateCrefoPayOrderId($quoteTransfer);
    }
}
