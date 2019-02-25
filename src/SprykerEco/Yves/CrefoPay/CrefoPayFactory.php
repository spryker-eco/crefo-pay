<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use SprykerEco\Yves\CrefoPay\Dependency\Service\CrefoPayToCrefoPayApiServiceInterface;
use SprykerEco\Yves\CrefoPay\Form\BillSubForm;
use SprykerEco\Yves\CrefoPay\Form\CashOnDeliverySubForm;
use SprykerEco\Yves\CrefoPay\Form\CreditCard3DSubForm;
use SprykerEco\Yves\CrefoPay\Form\CreditCardSubForm;
use SprykerEco\Yves\CrefoPay\Form\DataProvider\BillFormDataProvider;
use SprykerEco\Yves\CrefoPay\Form\DataProvider\CashOnDeliveryFormDataProvider;
use SprykerEco\Yves\CrefoPay\Form\DataProvider\CreditCard3DFormDataProvider;
use SprykerEco\Yves\CrefoPay\Form\DataProvider\CreditCardFormDataProvider;
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
use SprykerEco\Yves\CrefoPay\Processor\Notification\CrefoPayNotificationProcessor;
use SprykerEco\Yves\CrefoPay\Processor\Notification\CrefoPayNotificationProcessorInterface;
use SprykerEco\Yves\CrefoPay\Processor\Notification\Mapper\CrefoPayNotificationProcessorMapper;
use SprykerEco\Yves\CrefoPay\Processor\Notification\Mapper\CrefoPayNotificationProcessorMapperInterface;
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
     * @return \SprykerEco\Yves\CrefoPay\Processor\Notification\CrefoPayNotificationProcessorInterface
     */
    public function createCrefoPayNotificationProcessor(): CrefoPayNotificationProcessorInterface
    {
        return new CrefoPayNotificationProcessor(
            $this->createCrefoPayNotificationProcessorMapper(),
            $this->getClient(),
            $this->getCrefoPayApiService(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Yves\CrefoPay\Processor\Notification\Mapper\CrefoPayNotificationProcessorMapperInterface
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
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createCreditCardForm(): SubFormInterface
    {
        return new CreditCardSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createCreditCard3DForm(): SubFormInterface
    {
        return new CreditCard3DSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createBillFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new BillFormDataProvider();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createCashOnDeliveryFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new CashOnDeliveryFormDataProvider();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createDirectDebitFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new DirectDebitFormDataProvider($this->getConfig());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPayPalFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new PayPalFormDataProvider();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPrepaidFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new PrepaidFormDataProvider();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createSofortFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new SofortFormDataProvider();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createCreditCardFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new CreditCardFormDataProvider($this->getConfig());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createCreditCard3DFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new CreditCard3DFormDataProvider($this->getConfig());
    }

    /**
     * @return \SprykerEco\Yves\CrefoPay\Dependency\Service\CrefoPayToCrefoPayApiServiceInterface
     */
    public function getCrefoPayApiService(): CrefoPayToCrefoPayApiServiceInterface
    {
        return $this->getProvidedDependency(CrefoPayDependencyProvider::SERVICE_CREFO_PAY_API);
    }
}
