<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Payment\Filter;

use ArrayObject;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\CrefoPay\Business\Mapper\PaymentMethod\CrefoPayPaymentMethodMapperInterface;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;

class CrefoPayPaymentMethodFilter implements CrefoPayPaymentMethodFilterInterface
{
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
        $this->config = $config;
        $this->paymentMethodMapper = $paymentMethodMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(
        PaymentMethodsTransfer $paymentMethodsTransfer,
        QuoteTransfer $quoteTransfer
    ): PaymentMethodsTransfer {
        $result = new ArrayObject();

        foreach ($paymentMethodsTransfer->getMethods() as $paymentMethod) {
            if ($this->isPaymentProviderCrefoPay($paymentMethod) && !$this->isAvailable($paymentMethod, $quoteTransfer)) {
                continue;
            }

            $result->append($paymentMethod);
        }

        $paymentMethodsTransfer->setMethods($result);

        return $paymentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isAvailable(PaymentMethodTransfer $paymentMethodTransfer, QuoteTransfer $quoteTransfer): bool
    {
        $allowedPaymentMethods = $quoteTransfer->getCrefoPayTransaction()->getAllowedPaymentMethods();
        $externalPaymentMethodName = $this->paymentMethodMapper
            ->mapInternalToExternalPaymentMethodName(
                $paymentMethodTransfer->getMethodName()
            );

        return in_array($externalPaymentMethodName, $allowedPaymentMethods);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return bool
     */
    protected function isPaymentProviderCrefoPay(PaymentMethodTransfer $paymentMethodTransfer): bool
    {
        return strpos($paymentMethodTransfer->getMethodName(), $this->config->getProviderName()) !== false;
    }
}
