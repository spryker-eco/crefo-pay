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
use SprykerEco\Zed\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface;

class CrefoPayQuoteExpanderMapper implements CrefoPayQuoteExpanderMapperInterface
{
    protected const INTEGRATION_TYPE = 'SecureFields';
    protected const AUTO_CAPTURE = 'false';
    protected const CONTEXT = 'ONLINE';
    protected const USER_TYPE_PRIVATE = 'PRIVATE';
    protected const USER_TYPE_BUSINESS = 'BUSINESS';
    protected const SALUTATION_MAPPING = ['Mr' => 'M', 'Ms' => 'F', 'Mrs' => 'F', 'Dr' => 'M'];
    protected const AVAILABLE_LOCALES = ['EN', 'DE', 'ES', 'FR', 'IT', 'NL'];
    protected const DEFAULT_LOCALE = 'EN';
    protected const SHIPPING_COSTS_DESCRIPTION = 'Shipping Costs';
    protected const SHIPPING_COSTS_COUNT = 1;
    protected const GUEST_USER_ID_LENGTH = 6;
    protected const GUEST_USER_ID_PATTERN = 'GUEST-USER-%s';
    protected const USER_ID_B2B_SUFFIX = '-B2B';
    protected const USER_ID_B2C_SUFFIX = '-B2C';
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @var \SprykerEco\Service\CrefoPay\CrefoPayServiceInterface
     */
    protected $crefoPayService;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @var \SprykerEco\Zed\CrefoPay\CrefoPayConfig
     */
    protected $config;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \SprykerEco\Service\CrefoPay\CrefoPayServiceInterface $crefoPayService
     * @param \SprykerEco\Zed\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface $utilTextService
     * @param \SprykerEco\Zed\CrefoPay\CrefoPayConfig $config
     * @param \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        CrefoPayServiceInterface $crefoPayService,
        CrefoPayToUtilTextServiceInterface $utilTextService,
        CrefoPayConfig $config,
        CrefoPayToLocaleFacadeInterface $localeFacade
    ) {
        $this->crefoPayService = $crefoPayService;
        $this->utilTextService = $utilTextService;
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
        $createTransactionRequestTransfer = (new CrefoPayApiCreateTransactionRequestTransfer())
            ->setMerchantID($this->config->getMerchantId())
            ->setStoreID($this->config->getStoreId())
            ->setOrderID($this->generateCrefoPayOrderId($quoteTransfer))
            ->setUserID($this->getUserId($quoteTransfer))
            ->setIntegrationType(static::INTEGRATION_TYPE)
            ->setAutoCapture(static::AUTO_CAPTURE)
            ->setContext(static::CONTEXT)
            ->setUserType($this->getUserType())
            ->setUserRiskClass($this->config->getUserRiskClass())
            ->setUserIpAddress($quoteTransfer->getCrefoPayTransaction()->getClientIp())
            ->setBillingAddress($this->getBillingAddress($quoteTransfer))
            ->setShippingAddress($this->getShippingAddress($quoteTransfer))
            ->setAmount($this->createCrefoPayApiAmountTransfer($quoteTransfer->getTotals()))
            ->setBasketItems($this->createBasket($quoteTransfer))
            ->setLocale($this->getLocale());

        return $this->addUserInformation($quoteTransfer, $createTransactionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function generateCrefoPayOrderId(QuoteTransfer $quoteTransfer): string
    {
        return $this->crefoPayService->generateCrefoPayOrderId(
            $quoteTransfer,
            $this->config->getUseIndependentOrderIdForTransaction()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getUserId(QuoteTransfer $quoteTransfer): string
    {
        $userId = $quoteTransfer->getCustomerReference();

        if ($quoteTransfer->getCustomer()->getIsGuest() || $userId === null) {
            $userId = $this->createGuestUserId();
        }

        return $this->addUserTypeSuffix($userId);
    }

    /**
     * @return string
     */
    protected function createGuestUserId(): string
    {
        return sprintf(
            static::GUEST_USER_ID_PATTERN,
            $this->utilTextService->generateRandomString(static::GUEST_USER_ID_LENGTH)
        );
    }

    /**
     * @param string $userId
     *
     * @return string
     */
    protected function addUserTypeSuffix(string $userId): string
    {
        if ($this->config->getIsBusinessToBusiness()) {
            return $userId . static::USER_ID_B2B_SUFFIX;
        }

        return $userId . static::USER_ID_B2C_SUFFIX;
    }

    /**
     * @return string
     */
    protected function getUserType(): string
    {
        if ($this->config->getIsBusinessToBusiness()) {
            return static::USER_TYPE_BUSINESS;
        }

        return static::USER_TYPE_PRIVATE;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CrefoPayApiCreateTransactionRequestTransfer $createTransactionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiCreateTransactionRequestTransfer
     */
    protected function addUserInformation(
        QuoteTransfer $quoteTransfer,
        CrefoPayApiCreateTransactionRequestTransfer $createTransactionRequestTransfer
    ): CrefoPayApiCreateTransactionRequestTransfer {
        if ($this->config->getIsBusinessToBusiness()) {
            $createTransactionRequestTransfer->setCompanyData(
                $quoteTransfer->getCrefoPayCompany()
            );

            return $createTransactionRequestTransfer;
        }

        $createTransactionRequestTransfer->setUserData(
            $this->createCrefoPayApiPersonTransfer($quoteTransfer)
        );

        return $createTransactionRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiPersonTransfer
     */
    protected function createCrefoPayApiPersonTransfer(QuoteTransfer $quoteTransfer): CrefoPayApiPersonTransfer
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

        $basket = new ArrayObject($items);
        $basket->append($this->createShippingCostItem($quoteTransfer));

        return $basket;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiBasketItemTransfer
     */
    protected function createShippingCostItem(QuoteTransfer $quoteTransfer): CrefoPayApiBasketItemTransfer
    {
        return (new CrefoPayApiBasketItemTransfer())
            ->setBasketItemType($this->config->getProductTypeShippingCosts())
            ->setBasketItemText(static::SHIPPING_COSTS_DESCRIPTION)
            ->setBasketItemCount(static::SHIPPING_COSTS_COUNT)
            ->setBasketItemAmount($this->getShippingAmount($quoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiAmountTransfer|null
     */
    protected function getShippingAmount(QuoteTransfer $quoteTransfer): ?CrefoPayApiAmountTransfer
    {
        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() === static::SHIPMENT_EXPENSE_TYPE) {
                return (new CrefoPayApiAmountTransfer())
                    ->setAmount($expenseTransfer->getSumPriceToPayAggregation())
                    ->setVatRate($expenseTransfer->getTaxRate())
                    ->setVatAmount($expenseTransfer->getSumTaxAmount());
            }
        }

        return null;
    }

    /**
     * @return string
     */
    protected function getLocale(): string
    {
        $locale = strtoupper(substr($this->localeFacade->getCurrentLocaleName(), 0, 2));
        if (in_array($locale, static::AVAILABLE_LOCALES)) {
            return $locale;
        }

        return static::DEFAULT_LOCALE;
    }
}
