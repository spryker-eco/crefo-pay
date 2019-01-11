<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Zed\CrefoPay\Business\Payment\Filter\CrefoPayPaymentMethodFilter;
use SprykerEco\Zed\CrefoPay\Business\Payment\Filter\CrefoPayPaymentMethodFilterInterface;
use SprykerEco\Zed\CrefoPay\Business\Quote\Expander\CrefoPayQuoteExpander;
use SprykerEco\Zed\CrefoPay\Business\Quote\Expander\CrefoPayQuoteExpanderInterface;
use SprykerEco\Zed\CrefoPay\Business\Quote\Expander\Mapper\CrefoPayQuoteExpanderMapper;
use SprykerEco\Zed\CrefoPay\Business\Quote\Expander\Mapper\CrefoPayQuoteExpanderMapperInterface;
use SprykerEco\Zed\CrefoPay\CrefoPayDependencyProvider;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCrefoPayApiFacadeInterface;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToLocaleFacadeInterface;

/**
 * @method \SprykerEco\Zed\CrefoPay\CrefoPayConfig getConfig()
 */
class CrefoPayBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Quote\Expander\CrefoPayQuoteExpanderInterface
     */
    public function createQuoteExpander(): CrefoPayQuoteExpanderInterface
    {
        return new CrefoPayQuoteExpander(
            $this->createQuoteExpanderMapper(),
            $this->getCrefoPayApiFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Quote\Expander\Mapper\CrefoPayQuoteExpanderMapperInterface
     */
    public function createQuoteExpanderMapper(): CrefoPayQuoteExpanderMapperInterface
    {
        return new CrefoPayQuoteExpanderMapper(
            $this->getConfig(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Payment\Filter\CrefoPayPaymentMethodFilterInterface
     */
    public function createPaymentMethodFilter(): CrefoPayPaymentMethodFilterInterface
    {
        return new CrefoPayPaymentMethodFilter($this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCrefoPayApiFacadeInterface
     */
    public function getCrefoPayApiFacade(): CrefoPayToCrefoPayApiFacadeInterface
    {
        return $this->getProvidedDependency(CrefoPayDependencyProvider::FACADE_CREFO_APY_API);
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToLocaleFacadeInterface
     */
    public function getLocaleFacade(): CrefoPayToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(CrefoPayDependencyProvider::FACADE_LOCALE);
    }
}
