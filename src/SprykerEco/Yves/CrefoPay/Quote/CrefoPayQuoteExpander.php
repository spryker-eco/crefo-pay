<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Quote;

use Generated\Shared\Transfer\CrefoPayTransactionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Client\CrefoPay\CrefoPayClientInterface;
use Symfony\Component\HttpFoundation\Request;

class CrefoPayQuoteExpander implements CrefoPayQuoteExpanderInterface
{
    /**
     * @var \SprykerEco\Client\CrefoPay\CrefoPayClientInterface
     */
    protected $client;

    /**
     * @param \SprykerEco\Client\CrefoPay\CrefoPayClientInterface $client
     */
    public function __construct(CrefoPayClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expand(Request $request, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer->setCrefoPayTransaction(
            $this->createCrefoPayTransactionTransfer($request)
        );

        return $this->client->startCrefoPayTransaction($quoteTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CrefoPayTransactionTransfer
     */
    protected function createCrefoPayTransactionTransfer(Request $request): CrefoPayTransactionTransfer
    {
        return (new CrefoPayTransactionTransfer())
            ->setClientIp($request->getClientIp());
    }
}
