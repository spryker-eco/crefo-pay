<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Form\DataProvider;

use Generated\Shared\Transfer\CrefoPayPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerEco\Yves\CrefoPay\CrefoPayConfig;
use SprykerEco\Yves\CrefoPay\Form\CashOnDeliverySubForm;

class CashOnDeliveryFormDataProvider implements StepEngineFormDataProviderInterface
{
    /**
     * @var \SprykerEco\Yves\CrefoPay\CrefoPayConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Yves\CrefoPay\CrefoPayConfig $config
     */
    public function __construct(CrefoPayConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getPayment() === null) {
            $quoteTransfer->setPayment(new PaymentTransfer());
        }

        if ($quoteTransfer->getPayment()->getCrefoPayCashOnDelivery() !== null) {
            return $quoteTransfer;
        }

        $quoteTransfer->getPayment()->setCrefoPayCashOnDelivery(new CrefoPayPaymentTransfer());

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer): array
    {
        return [
            CashOnDeliverySubForm::CREFO_PAY_SHOP_PUBLIC_KEY => $this->config->getPublicKey(),
            CashOnDeliverySubForm::CREFO_PAY_ORDER_ID => $quoteTransfer->getCrefoPayTransaction()->getCrefoPayOrderId(),
            CashOnDeliverySubForm::CREFO_PAY_SECURE_FIELDS_API_ENDPOINT => $this->config->getSecureFieldsApiEndpoint(),
            CashOnDeliverySubForm::CREFO_PAY_SECURE_FIELDS_PLACEHOLDERS => $this->config->getSecureFieldsPlaceholders(),
        ];
    }
}
