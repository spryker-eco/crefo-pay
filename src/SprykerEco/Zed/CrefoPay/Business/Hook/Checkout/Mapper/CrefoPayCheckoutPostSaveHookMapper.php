<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Hook\Checkout\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CrefoPayApiAmountTransfer;
use Generated\Shared\Transfer\CrefoPayApiBasketItemTransfer;
use Generated\Shared\Transfer\CrefoPayApiRequestTransfer;
use Generated\Shared\Transfer\CrefoPayApiReserveRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerEco\Shared\CrefoPay\CrefoPayConfig as SharedCrefoPayConfig;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;

class CrefoPayCheckoutPostSaveHookMapper implements CrefoPayCheckoutHookMapperInterface
{
    /**
     * @var \SprykerEco\Zed\CrefoPay\CrefoPayConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\CrefoPay\CrefoPayConfig $config
     */
    public function __construct(CrefoPayConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CrefoPayApiRequestTransfer $requestTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiRequestTransfer
     */
    public function mapQuoteTransferToRequestTransfer(
        QuoteTransfer $quoteTransfer,
        CrefoPayApiRequestTransfer $requestTransfer
    ): CrefoPayApiRequestTransfer {
        $requestTransfer->setReserveRequest(
            $this->createReserveRequestTransfer($quoteTransfer)
        );

        return $requestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiReserveRequestTransfer
     */
    protected function createReserveRequestTransfer(QuoteTransfer $quoteTransfer): CrefoPayApiReserveRequestTransfer
    {
        return (new CrefoPayApiReserveRequestTransfer())
            ->setMerchantID($this->config->getMerchantId())
            ->setStoreID($this->config->getStoreId())
            ->setOrderID($quoteTransfer->getCrefoPayTransaction()->getCrefoPayOrderId())
            ->setPaymentMethod($this->getPaymentMethod($quoteTransfer))
            ->setAmount($this->createCrefoPayApiAmountTransfer($quoteTransfer->getTotals()))
            ->setBasketItems($this->createBasket($quoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    protected function getPaymentMethod(QuoteTransfer $quoteTransfer): ?string
    {
        return array_search(
            $quoteTransfer->getPayment()->getPaymentSelection(),
            $this->config->getAvailablePaymentMethodsMapping()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiAmountTransfer
     */
    protected function createCrefoPayApiAmountTransfer(TotalsTransfer $totalsTransfer): CrefoPayApiAmountTransfer
    {
        return (new CrefoPayApiAmountTransfer())
            ->setAmount($totalsTransfer->getPriceToPay())
            ->setVatRate($totalsTransfer->getTaxTotal()->getTaxRate())
            ->setVatAmount($totalsTransfer->getTaxTotal()->getAmount());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject
     */
    protected function createBasket(QuoteTransfer $quoteTransfer): ArrayObject
    {
        $items = array_map(
            function (ItemTransfer $itemTransfer) {
                $amount = (new CrefoPayApiAmountTransfer())
                    ->setAmount($itemTransfer->getSumPriceToPayAggregation())
                    ->setVatRate($itemTransfer->getTaxRate())
                    ->setVatAmount($itemTransfer->getSumTaxAmountFullAggregation());

                return (new CrefoPayApiBasketItemTransfer())
                    ->setBasketItemType(SharedCrefoPayConfig::PRODUCT_TYPE_DEFAULT)
                    ->setBasketItemRiskClass(SharedCrefoPayConfig::PRODUCT_RISK_CLASS)
                    ->setBasketItemText($itemTransfer->getName())
                    ->setBasketItemID($itemTransfer->getSku())
                    ->setBasketItemCount($itemTransfer->getQuantity())
                    ->setBasketItemAmount($amount);
            },
            $quoteTransfer->getItems()->getArrayCopy()
        );

        return new ArrayObject($items);
    }
}
