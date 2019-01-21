<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use SprykerEco\Yves\CrefoPay\Dependency\Client\CrefoPayToQuoteClientInterface;
use SprykerEco\Yves\CrefoPay\Dependency\Service\CrefoPayToUtilEncodingServiceInterface;
use SprykerEco\Yves\CrefoPay\Form\BillSubForm;
use SprykerEco\Yves\CrefoPay\Form\CashOnDeliverySubForm;
use SprykerEco\Yves\CrefoPay\Form\DataProvider\BillFormDataProvider;
use SprykerEco\Yves\CrefoPay\Form\DataProvider\CashOnDeliveryFormDataProvider;
use SprykerEco\Yves\CrefoPay\Form\DataProvider\DirectDebitFormDataProvider;
use SprykerEco\Yves\CrefoPay\Form\DataProvider\PayPalFormDataProvider;
use SprykerEco\Yves\CrefoPay\Form\DataProvider\PrepaidFormDataProvider;
use SprykerEco\Yves\CrefoPay\Form\DataProvider\SofortFormDataProvider;
use SprykerEco\Yves\CrefoPay\Form\DirectDebitSubForm;
use SprykerEco\Yves\CrefoPay\Form\PayPalSubForm;
use SprykerEco\Yves\CrefoPay\Form\PrepaidSubForm;
use SprykerEco\Yves\CrefoPay\Form\SofortSubForm;
use SprykerEco\Yves\CrefoPay\Payment\CrefoPayPaymentExpander;
use SprykerEco\Yves\CrefoPay\Payment\CrefoPayPaymentExpanderInterface;
use SprykerEco\Yves\CrefoPay\Processor\CrefoPayNotificationProcessor;
use SprykerEco\Yves\CrefoPay\Processor\CrefoPayNotificationProcessorInterface;
use SprykerEco\Yves\CrefoPay\Processor\Mapper\CrefoPayNotificationProcessorMapper;
use SprykerEco\Yves\CrefoPay\Processor\Mapper\CrefoPayNotificationProcessorMapperInterface;
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

    /**
     * @return \SprykerEco\Yves\CrefoPay\Payment\CrefoPayPaymentExpanderInterface
     */
    public function createPaymentExpander(): CrefoPayPaymentExpanderInterface
    {
        return new CrefoPayPaymentExpander();
    }

    /**
     * @return \SprykerEco\Yves\CrefoPay\Processor\CrefoPayNotificationProcessorInterface
     */
    public function createCrefoPayNotificationProcessor(): CrefoPayNotificationProcessorInterface
    {
        return new CrefoPayNotificationProcessor(
            $this->createCrefoPayNotificationProcessorMapper(),
            $this->getClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\CrefoPay\Processor\Mapper\CrefoPayNotificationProcessorMapperInterface
     */
    public function createCrefoPayNotificationProcessorMapper(): CrefoPayNotificationProcessorMapperInterface
    {
        return new CrefoPayNotificationProcessorMapper();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createBillForm(): SubFormInterface
    {
        return new BillSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createCashOnDeliveryForm(): SubFormInterface
    {
        return new CashOnDeliverySubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createDirectDebitForm(): SubFormInterface
    {
        return new DirectDebitSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createPayPalForm(): SubFormInterface
    {
        return new PayPalSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createPrepaidForm(): SubFormInterface
    {
        return new PrepaidSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createSofortForm(): SubFormInterface
    {
        return new SofortSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createBillFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new BillFormDataProvider($this->getQuoteClient());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createCashOnDeliveryFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new CashOnDeliveryFormDataProvider($this->getQuoteClient());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createDirectDebitFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new DirectDebitFormDataProvider($this->getQuoteClient());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPayPalFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new PayPalFormDataProvider($this->getQuoteClient());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPrepaidFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new PrepaidFormDataProvider($this->getQuoteClient());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createSofortFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new SofortFormDataProvider($this->getQuoteClient());
    }

    /**
     * @return \SprykerEco\Yves\CrefoPay\Dependency\Client\CrefoPayToQuoteClientInterface
     */
    public function getQuoteClient(): CrefoPayToQuoteClientInterface
    {
        return $this->getProvidedDependency(CrefoPayDependencyProvider::CLIENT_QUOTE);
    }
}
