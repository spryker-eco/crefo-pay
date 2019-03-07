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
use SprykerEco\Zed\CrefoPay\Business\Mapper\PaymentMethod\CrefoPayPaymentMethodMapperInterface;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;

class CrefoPayCheckoutPostSaveHookMapper implements CrefoPayCheckoutHookMapperInterface
{
    protected const GET_PAYMENT_METHOD_PATTERN = 'get%s';

    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Mapper\PaymentMethod\CrefoPayPaymentMethodMapperInterface
     */
    protected $paymentMethodMapper;

    /**
     * @var \SprykerEco\Zed\CrefoPay\CrefoPayConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Business\Mapper\PaymentMethod\CrefoPayPaymentMethodMapperInterface $paymentMethodMapper
     * @param \SprykerEco\Zed\CrefoPay\CrefoPayConfig $config
     */
    public function __construct(
        CrefoPayPaymentMethodMapperInterface $paymentMethodMapper,
        CrefoPayConfig $config
    ) {
        $this->paymentMethodMapper = $paymentMethodMapper;
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
            ->setPaymentMethod($this->getPaymentMethodNew($quoteTransfer))
            ->setPaymentInstrumentID($this->getPaymentInstrumentId($quoteTransfer))
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
        return $this->paymentMethodMapper->mapInternalToExternalPaymentMethodName(
            $quoteTransfer->getPayment()->getPaymentSelection()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    protected function getPaymentInstrumentId(QuoteTransfer $quoteTransfer): ?string
    {
        $method = sprintf(
            static::GET_PAYMENT_METHOD_PATTERN,
            ucfirst($quoteTransfer->getPayment()->getPaymentSelection())
        );

        if (!method_exists($quoteTransfer->getPayment(), $method)) {
            return null;
        }

        return $quoteTransfer->getPayment()->$method()->getPaymentInstrumentId();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    protected function getPaymentMethodNew(QuoteTransfer $quoteTransfer): ?string
    {
        $method = sprintf(
            static::GET_PAYMENT_METHOD_PATTERN,
            ucfirst($quoteTransfer->getPayment()->getPaymentSelection())
        );

        if (!method_exists($quoteTransfer->getPayment(), $method)) {
            return null;
        }

        return $quoteTransfer->getPayment()->$method()->getPaymentMethod();
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
                    ->setBasketItemType($this->config->getProductTypeDefault())
                    ->setBasketItemRiskClass($this->config->getProductRiskClass())
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
