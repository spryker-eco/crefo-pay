<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerEco\Yves\CrefoPay\Quote\CrefoPayQuoteExpander;
use SprykerEco\Yves\CrefoPay\Quote\CrefoPayQuoteExpanderInterface;

/**
 * @method \SprykerEco\Yves\CrefoPay\CrefoPayConfig getConfig()
 * @method \SprykerEco\Client\CrefoPay\CrefoPayClientInterface getClient()
 */
class CrefoPayFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Yves\CrefoPay\Quote\CrefoPayQuoteExpanderInterface
     */
    public function createQuoteExpander(): CrefoPayQuoteExpanderInterface
    {
        return new CrefoPayQuoteExpander($this->getClient());
    }
}
