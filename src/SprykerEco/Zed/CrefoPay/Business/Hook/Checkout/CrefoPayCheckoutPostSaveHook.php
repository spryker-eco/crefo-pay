<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Hook\Checkout;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CrefoPayApiRequestTransfer;
use Generated\Shared\Transfer\CrefoPayApiResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\CrefoPay\CrefoPayConfig;
use SprykerEco\Zed\CrefoPay\Business\Hook\Checkout\Mapper\CrefoPayCheckoutHookMapperInterface;
use SprykerEco\Zed\CrefoPay\Business\Hook\Checkout\Saver\CrefoPayCheckoutHookSaverInterface;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCrefoPayApiFacadeInterface;

class CrefoPayCheckoutPostSaveHook implements CrefoPayCheckoutHookInterface
{
    /**
     * @var string
     */
    protected const ERROR_TYPE_PAYMENT_FAILED = 'payment failed';
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PAYMENT_FAILED = 'Something went wrong with your payment. Try again!';

    /**
     * @var \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCrefoPayApiFacadeInterface
     */
    protected $crefoPayApiFacade;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Hook\Checkout\Mapper\CrefoPayCheckoutHookMapperInterface
     */
    protected $mapper;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Hook\Checkout\Saver\CrefoPayCheckoutHookSaverInterface
     */
    protected $saver;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCrefoPayApiFacadeInterface $crefoPayApiFacade
     * @param \SprykerEco\Zed\CrefoPay\Business\Hook\Checkout\Mapper\CrefoPayCheckoutHookMapperInterface $mapper
     * @param \SprykerEco\Zed\CrefoPay\Business\Hook\Checkout\Saver\CrefoPayCheckoutHookSaverInterface $saver
     */
    public function __construct(
        CrefoPayToCrefoPayApiFacadeInterface $crefoPayApiFacade,
        CrefoPayCheckoutHookMapperInterface $mapper,
        CrefoPayCheckoutHookSaverInterface $saver
    ) {
        $this->crefoPayApiFacade = $crefoPayApiFacade;
        $this->mapper = $mapper;
        $this->saver = $saver;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function execute(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): void
    {
        if ($quoteTransfer->getPayment()->getPaymentProvider() !== CrefoPayConfig::PROVIDER_NAME) {
            return;
        }

        $requestTransfer = $this->mapper
            ->mapQuoteTransferToRequestTransfer(
                $quoteTransfer,
                new CrefoPayApiRequestTransfer()
            );

        $responseTransfer = $this->crefoPayApiFacade->performReserveApiCall($requestTransfer);
        $this->saver->savePaymentEntities($requestTransfer, $responseTransfer);

        if (!$responseTransfer->getIsSuccess()) {
            $this->processFailureResponse($checkoutResponseTransfer);

            return;
        }

        if (!$this->isMethodWithRedirect($responseTransfer)) {
            return;
        }

        $this->setRedirectToPaymentSystem($checkoutResponseTransfer, $responseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayApiResponseTransfer $responseTransfer
     *
     * @return bool
     */
    protected function isMethodWithRedirect(CrefoPayApiResponseTransfer $responseTransfer): bool
    {
        return !empty($responseTransfer->getReserveResponse()->getRedirectUrl());
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Generated\Shared\Transfer\CrefoPayApiResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function setRedirectToPaymentSystem(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        CrefoPayApiResponseTransfer $responseTransfer
    ): void {
        $checkoutResponseTransfer->setIsExternalRedirect(true);
        $checkoutResponseTransfer->setRedirectUrl(
            $responseTransfer->getReserveResponse()->getRedirectUrl()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function processFailureResponse(
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): void {
        $error = (new CheckoutErrorTransfer())
            ->setErrorType(static::ERROR_TYPE_PAYMENT_FAILED)
            ->setMessage(static::ERROR_MESSAGE_PAYMENT_FAILED);

        $checkoutResponseTransfer->setIsSuccess(false);
        $checkoutResponseTransfer->addError($error);
    }
}
