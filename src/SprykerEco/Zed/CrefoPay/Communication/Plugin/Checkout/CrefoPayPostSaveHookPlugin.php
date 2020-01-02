<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \SprykerEco\Zed\CrefoPay\Business\CrefoPayFacadeInterface getFacade()
 * @method \SprykerEco\Zed\CrefoPay\Communication\CrefoPayCommunicationFactory getFactory()
 * @method \SprykerEco\Zed\CrefoPay\CrefoPayConfig getConfig()
 *
 * @SuppressWarnings(PHPMD.NewPluginExtensionModuleRule)
 */
class CrefoPayPostSaveHookPlugin extends AbstractPlugin implements CheckoutPostSaveHookInterface
{
    /**
     * {@inheritDoc}
     * - Makes reserve request to CrefoPay API.
     * - Updates payment entities and saves them to DB.
     * - Updates order items with necessary OMS statuses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function executeHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFacade()->executePostSaveHook($quoteTransfer, $checkoutResponse);
    }
}
