<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Communication\Plugin;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Payment\Dependency\Plugin\Payment\PaymentMethodFilterPluginInterface;

/**
 * @method \SprykerEco\Zed\CrefoPay\Business\CrefoPayFacadeInterface getFacade()
 * @method \SprykerEco\Zed\CrefoPay\Communication\CrefoPayCommunicationFactory getFactory()
 */
class CrefoPayPaymentMethodFilterPlugin extends AbstractPlugin implements PaymentMethodFilterPluginInterface
{
    /**
     * Specification:
     * - Returns filtered by set of plugins array object of payments
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(
        PaymentMethodsTransfer $paymentMethodsTransfer,
        QuoteTransfer $quoteTransfer
    ): PaymentMethodsTransfer {
        return $this->getFacade()->filterPaymentMethods($paymentMethodsTransfer, $quoteTransfer);
    }
}
