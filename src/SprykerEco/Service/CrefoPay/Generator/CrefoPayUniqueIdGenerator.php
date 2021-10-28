<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Service\CrefoPay\Generator;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Service\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface;

class CrefoPayUniqueIdGenerator implements CrefoPayUniqueIdGeneratorInterface
{
    /**
     * @var int
     */
    protected const CREFO_PAY_ORDER_ID_LENGTH = 30;

    /**
     * \SprykerEco\Service\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface $utilTextService
     */
    protected $utilTextService;

    /**
     * @param \SprykerEco\Service\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface $utilTextService
     */
    public function __construct(CrefoPayToUtilTextServiceInterface $utilTextService)
    {
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    public function generateCrefoPayOrderId(QuoteTransfer $quoteTransfer): string
    {
        $randomStringLength = static::CREFO_PAY_ORDER_ID_LENGTH - strlen($quoteTransfer->getCustomerReference()) - 1;

        return sprintf(
            '%s-%s',
            $this->utilTextService->generateRandomString($randomStringLength),
            $quoteTransfer->getCustomerReference(),
        );
    }
}
