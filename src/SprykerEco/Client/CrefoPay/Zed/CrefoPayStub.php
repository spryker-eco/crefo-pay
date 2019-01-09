<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\CrefoPay\Zed;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Client\CrefoPay\Dependency\Client\CrefoPayToZedRequestClientInterface;

class CrefoPayStub implements CrefoPayStubInterface
{
    /**
     * @var \SprykerEco\Client\CrefoPay\Dependency\Client\CrefoPayToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \SprykerEco\Client\CrefoPay\Dependency\Client\CrefoPayToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(CrefoPayToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function startCrefoPayTransaction(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->zedRequestClient->call('/crefo-pay/gateway/start-crefo-pay-transaction', $quoteTransfer);

        return $quoteTransfer;
    }
}
