<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Plugin\StepEngine;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Yves\CrefoPay\CrefoPayFactory getFactory()
 *
 * @SuppressWarnings(PHPMD.NewPluginExtensionModuleRule)
 */
class CrefoPayQuoteExpanderPlugin extends AbstractPlugin implements StepHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `QuoteTransfer` with response from CreateTransaction API call.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addToDataClass(Request $request, AbstractTransfer $dataTransfer)
    {
        return $this->getFactory()
            ->createQuoteExpander()
            ->expand($request, $dataTransfer);
    }
}
