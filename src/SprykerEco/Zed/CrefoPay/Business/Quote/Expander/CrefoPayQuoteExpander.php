<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Quote\Expander;

use Generated\Shared\Transfer\CrefoPayApiRequestTransfer;
use Generated\Shared\Transfer\CrefoPayApiResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\CrefoPay\Business\Quote\Expander\Mapper\CrefoPayQuoteExpanderMapperInterface;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCrefoPayApiFacadeInterface;

class CrefoPayQuoteExpander implements CrefoPayQuoteExpanderInterface
{
    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Quote\Expander\Mapper\CrefoPayQuoteExpanderMapperInterface
     */
    protected $mapper;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCrefoPayApiFacadeInterface
     */
    protected $crefoPayApiFacade;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Business\Quote\Expander\Mapper\CrefoPayQuoteExpanderMapperInterface $mapper
     * @param \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCrefoPayApiFacadeInterface $crefoPayApiFacade
     */
    public function __construct(
        CrefoPayQuoteExpanderMapperInterface $mapper,
        CrefoPayToCrefoPayApiFacadeInterface $crefoPayApiFacade
    ) {
        $this->mapper = $mapper;
        $this->crefoPayApiFacade = $crefoPayApiFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expand(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $requestTransfer = $this->mapper
            ->mapQuoteTransferToRequestTransfer(
                $quoteTransfer,
                new CrefoPayApiRequestTransfer()
            );

        $quoteTransfer->getCrefoPayTransaction()
            ->setCrefoPayOrderId($requestTransfer->getCreateTransactionRequest()->getOrderID());

        $responseTransfer = $this->crefoPayApiFacade->performCreateTransactionApiCall($requestTransfer);

        if ($responseTransfer->getIsSuccess()) {
            $quoteTransfer = $this->addResponseDataToQuoteTransfer($quoteTransfer, $responseTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CrefoPayApiResponseTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addResponseDataToQuoteTransfer(
        QuoteTransfer $quoteTransfer,
        CrefoPayApiResponseTransfer $responseTransfer
    ): QuoteTransfer {
        $quoteTransfer->getCrefoPayTransaction()->fromArray(
            $responseTransfer->getCreateTransactionResponse()->toArray(true, true),
            true
        )
        ->setIsSuccess($responseTransfer->getIsSuccess());

        return $quoteTransfer;
    }
}
