<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Quote\Expander\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CrefoPayApiAddressTransfer;
use Generated\Shared\Transfer\CrefoPayApiAmountTransfer;
use Generated\Shared\Transfer\CrefoPayApiBasketItemTransfer;
use Generated\Shared\Transfer\CrefoPayApiCreateTransactionRequestTransfer;
use Generated\Shared\Transfer\CrefoPayApiPersonTransfer;
use Generated\Shared\Transfer\CrefoPayApiRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerEco\Service\CrefoPay\CrefoPayServiceInterface;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToLocaleFacadeInterface;

class CrefoPayQuoteExpanderMapper implements CrefoPayQuoteExpanderMapperInterface
{
    protected const SALUTATION_MAPPING = ['Mr' => 'M', 'Ms' => 'F', 'Mrs' => 'F', 'Dr' => 'M'];
    protected const AVAILABLE_LOCALES = ['EN', 'DE', 'ES', 'FR', 'IT', 'NL'];
    protected const DEFAULT_LOCALE = 'EN';

    /**
     * @var \SprykerEco\Service\CrefoPay\CrefoPayServiceInterface
     */
    protected $service;

    /**
     * @var \SprykerEco\Zed\CrefoPay\CrefoPayConfig
     */
    protected $config;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \SprykerEco\Service\CrefoPay\CrefoPayServiceInterface $service
     * @param \SprykerEco\Zed\CrefoPay\CrefoPayConfig $config
     * @param \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        CrefoPayServiceInterface $service,
        CrefoPayConfig $config,
        CrefoPayToLocaleFacadeInterface $localeFacade
    ) {
        $this->service = $service;
        $this->config = $config;
        $this->localeFacade = $localeFacade;
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
        $requestTransfer->setCreateTransactionRequest(
            $this->createCreateTransactionRequestTransfer($quoteTransfer)
        );

        return $requestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiCreateTransactionRequestTransfer
     */
    protected function createCreateTransactionRequestTransfer(QuoteTransfer $quoteTransfer): CrefoPayApiCreateTransactionRequestTransfer
    {
        return (new CrefoPayApiCreateTransactionRequestTransfer())
            ->setMerchantID($this->config->getMerchantId())
            ->setStoreID($this->config->getStoreId())
            ->setOrderID($this->generateCrefoPayOrderId($quoteTransfer))
            ->setUserID($quoteTransfer->getCustomerReference())
            ->setIntegrationType($this->config->getIntegrationType())
            ->setAutoCapture($this->config->getAutoCapture())
            ->setContext($this->config->getContext())
            ->setUserType($this->config->getUserType())
            ->setUserRiskClass($this->config->getUserRiskClass())
            ->setUserIpAddress($quoteTransfer->getCrefoPayTransaction()->getClientIp())
            ->setUserData($this->createCrefoPayApiPersonTransfer($quoteTransfer))
            ->setBillingAddress($this->getBillingAddress($quoteTransfer))
            ->setShippingAddress($this->getShippingAddress($quoteTransfer))
            ->setAmount($this->createCrefoPayApiAmountTransfer($quoteTransfer->getTotals()))
            ->setBasketItems($this->createBasket($quoteTransfer))
            ->setLocale($this->getLocale());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function generateCrefoPayOrderId(QuoteTransfer $quoteTransfer): string
    {
        return $this->service->generateCrefoPayOrderId($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiPersonTransfer
     */
    protected function createCrefoPayApiPersonTransfer(QuoteTransfer $quoteTransfer)
    {
        return (new CrefoPayApiPersonTransfer())
            ->setSalutation($this->getSalutation($quoteTransfer))
            ->setName($quoteTransfer->getBillingAddress()->getFirstName())
            ->setSurname($quoteTransfer->getBillingAddress()->getLastName())
            ->setDateOfBirth($quoteTransfer->getCustomer()->getDateOfBirth())
            ->setEmail($quoteTransfer->getCustomer()->getEmail());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    protected function getSalutation(QuoteTransfer $quoteTransfer): ?string
    {
        return static::SALUTATION_MAPPING[$quoteTransfer->getCustomer()->getSalutation()];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiAddressTransfer
     */
    protected function getBillingAddress(QuoteTransfer $quoteTransfer): CrefoPayApiAddressTransfer
    {
        return $this->createCrefoPayApiAddressTransfer($quoteTransfer->getBillingAddress());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiAddressTransfer
     */
    protected function getShippingAddress(QuoteTransfer $quoteTransfer): CrefoPayApiAddressTransfer
    {
        if ($quoteTransfer->getBillingSameAsShipping()) {
            return $this->createCrefoPayApiAddressTransfer($quoteTransfer->getBillingAddress());
        }

        return $this->createCrefoPayApiAddressTransfer($quoteTransfer->getShippingAddress());
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiAddressTransfer
     */
    protected function createCrefoPayApiAddressTransfer(AddressTransfer $addressTransfer): CrefoPayApiAddressTransfer
    {
        return (new CrefoPayApiAddressTransfer())
            ->setStreet($addressTransfer->getAddress1())
            ->setNo($addressTransfer->getAddress2())
            ->setAdditional($addressTransfer->getAddress3())
            ->setZip($addressTransfer->getZipCode())
            ->setCity($addressTransfer->getCity())
            ->setCountry($addressTransfer->getIso2Code());
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

    /**
     * @return string
     */
    protected function getLocale(): string
    {
        if (in_array($this->localeFacade->getCurrentLocaleName(), static::AVAILABLE_LOCALES)) {
            return $this->localeFacade->getCurrentLocaleName();
        }

        return static::DEFAULT_LOCALE;
    }
}
