<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Quote\Expander\Mapper;

use Generated\Shared\Transfer\CrefoPayApiCreateTransactionRequestTransfer;
use Generated\Shared\Transfer\CrefoPayApiPersonTransfer;
use Generated\Shared\Transfer\CrefoPayApiRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class CrefoPayQuoteExpanderMapper implements CrefoPayQuoteExpanderMapperInterface
{
    protected const SALUTATION_MAPPING = ['M' => '', 'F' => ''];

    /**
     * @var \SprykerEco\Zed\CrefoPay\CrefoPayConfig
     */
    protected $config;

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
            ->setOrderID($this->generateOrderReference($quoteTransfer))
            ->setUserID($quoteTransfer->getCustomerReference())
            ->setIntegrationType($this->config->getIntegrationType())
            ->setAutoCapture($this->config->getAutoCapture())
            ->setMerchantReference($this->generateReference())
            ->setContext($this->config->getContext())
            ->setUserType($this->config->getUserType())
            ->setUserRiskClass($this->config->getUserRiskClass())
            ->setUserIpAddress($quoteTransfer->getCrefoPayTransaction()->getClientIp())
            ->setUserData($this->createCrefoPayApiPersonTransfer($quoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function generateOrderReference(QuoteTransfer $quoteTransfer): string
    {
        return uniqid($quoteTransfer->getCustomerReference(), true);
    }

    protected function generateReference()
    {
        return 'reference';
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
            ->setEmail($quoteTransfer->getCustomer()->getEmail())
            ->setPhoneNumber($quoteTransfer->getBillingAddress()->getPhone());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    protected function getSalutation(QuoteTransfer $quoteTransfer): ?string
    {
        return array_search(
            $quoteTransfer->getCustomer()->getSalutation(),
            static::SALUTATION_MAPPING
        );
    }
}
