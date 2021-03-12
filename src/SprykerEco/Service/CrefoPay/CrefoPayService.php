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
     * @param bool $isIndependentGenerations
     *
     * @return string
     */
    public function generateCrefoPayOrderId(QuoteTransfer $quoteTransfer, bool $isIndependent): string
    {
        /** @var \SprykerEco\Service\CrefoPay\Generator\CrefoPayUniqueIdGeneratorInterface $generator */
        $generator = $this->getFactory()
            ->createUniqueIdGenerator();

        return $isIndependent ?
            $generator->generateCrefoPayOrderIdIndependent() :
            $generator->generateCrefoPayOrderId($quoteTransfer);
    }
}
