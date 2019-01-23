<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerEco\Service\CrefoPay\CrefoPayServiceInterface;
use SprykerEco\Zed\CrefoPay\Business\Hook\Checkout\CrefoPayCheckoutHookInterface;
use SprykerEco\Zed\CrefoPay\Business\Hook\Checkout\CrefoPayCheckoutPostSaveHook;
use SprykerEco\Zed\CrefoPay\Business\Hook\Checkout\Mapper\CrefoPayCheckoutHookMapperInterface;
use SprykerEco\Zed\CrefoPay\Business\Hook\Checkout\Mapper\CrefoPayCheckoutPostSaveHookMapper;
use SprykerEco\Zed\CrefoPay\Business\Hook\Checkout\Saver\CrefoPayCheckoutHookSaverInterface;
use SprykerEco\Zed\CrefoPay\Business\Hook\Checkout\Saver\CrefoPayCheckoutPostSaveHookSaver;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder\CaptureOmsCommandRequestBuilder;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder\CrefoPayOmsCommandRequestBuilderInterface;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\CaptureOmsCommand;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\CrefoPayOmsCommandInterface;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\CaptureOmsCommandSaver;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\CrefoPayOmsCommandSaverInterface;
use SprykerEco\Zed\CrefoPay\Business\Payment\Filter\CrefoPayPaymentMethodFilter;
use SprykerEco\Zed\CrefoPay\Business\Payment\Filter\CrefoPayPaymentMethodFilterInterface;
use SprykerEco\Zed\CrefoPay\Business\Payment\Saver\CrefoPayOrderPaymentSaver;
use SprykerEco\Zed\CrefoPay\Business\Payment\Saver\CrefoPayOrderPaymentSaverInterface;
use SprykerEco\Zed\CrefoPay\Business\Processor\CrefoPayNotificationProcessor;
use SprykerEco\Zed\CrefoPay\Business\Processor\CrefoPayNotificationProcessorInterface;
use SprykerEco\Zed\CrefoPay\Business\Oms\Mapper\CrefoPayOmsStatusMapper;
use SprykerEco\Zed\CrefoPay\Business\Oms\Mapper\CrefoPayOmsStatusMapperInterface;
use SprykerEco\Zed\CrefoPay\Business\Quote\Expander\CrefoPayQuoteExpander;
use SprykerEco\Zed\CrefoPay\Business\Quote\Expander\CrefoPayQuoteExpanderInterface;
use SprykerEco\Zed\CrefoPay\Business\Quote\Expander\Mapper\CrefoPayQuoteExpanderMapper;
use SprykerEco\Zed\CrefoPay\Business\Quote\Expander\Mapper\CrefoPayQuoteExpanderMapperInterface;
use SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReader;
use SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface;
use SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriter;
use SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface;
use SprykerEco\Zed\CrefoPay\CrefoPayDependencyProvider;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCrefoPayApiFacadeInterface;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToLocaleFacadeInterface;
use SprykerEco\Zed\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface;

/**
 * @method \SprykerEco\Zed\CrefoPay\CrefoPayConfig getConfig()
 * @method \SprykerEco\Zed\CrefoPay\Persistence\CrefoPayEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\CrefoPay\Persistence\CrefoPayRepositoryInterface getRepository()
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
            $this->getCrefoPayService(),
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
     * @return \SprykerEco\Zed\CrefoPay\Business\Processor\CrefoPayNotificationProcessorInterface
     */
    public function createCrefoPayNotificationProcessor(): CrefoPayNotificationProcessorInterface
    {
        return new CrefoPayNotificationProcessor(
            $this->createCrefoPayOmsStatusMapper(),
            $this->createReader(),
            $this->createWriter()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Mapper\CrefoPayOmsStatusMapperInterface
     */
    public function createCrefoPayOmsStatusMapper(): CrefoPayOmsStatusMapperInterface
    {
        return new CrefoPayOmsStatusMapper($this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Payment\Saver\CrefoPayOrderPaymentSaverInterface
     */
    public function createOrderPaymentSaver(): CrefoPayOrderPaymentSaverInterface
    {
        return new CrefoPayOrderPaymentSaver($this->createWriter());
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Hook\Checkout\CrefoPayCheckoutHookInterface
     */
    public function createCheckoutPostSaveHook(): CrefoPayCheckoutHookInterface
    {
        return new CrefoPayCheckoutPostSaveHook(
            $this->getCrefoPayApiFacade(),
            $this->createCheckoutPostSaveHookMapper(),
            $this->createCheckoutPostSaveHookSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Hook\Checkout\Mapper\CrefoPayCheckoutHookMapperInterface
     */
    public function createCheckoutPostSaveHookMapper(): CrefoPayCheckoutHookMapperInterface
    {
        return new CrefoPayCheckoutPostSaveHookMapper($this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Hook\Checkout\Saver\CrefoPayCheckoutHookSaverInterface
     */
    public function createCheckoutPostSaveHookSaver(): CrefoPayCheckoutHookSaverInterface
    {
        return new CrefoPayCheckoutPostSaveHookSaver(
            $this->createReader(),
            $this->createWriter(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface
     */
    public function createWriter(): CrefoPayWriterInterface
    {
        return new CrefoPayWriter(
            $this->getEntityManager(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface
     */
    public function createReader(): CrefoPayReaderInterface
    {
        return new CrefoPayReader($this->getRepository());
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Command\CrefoPayOmsCommandInterface
     */
    public function createCaptureOmsCommand(): CrefoPayOmsCommandInterface
    {
        return new CaptureOmsCommand(
            $this->createCaptureOmsCommandRequestBuilder(),
            $this->createReader(),
            $this->createCaptureOmsCommandSaver(),
            $this->getCrefoPayApiFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder\CrefoPayOmsCommandRequestBuilderInterface
     */
    public function createCaptureOmsCommandRequestBuilder(): CrefoPayOmsCommandRequestBuilderInterface
    {
        return new CaptureOmsCommandRequestBuilder(
            $this->getUtilTextService(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\CrefoPayOmsCommandSaverInterface
     */
    public function createCaptureOmsCommandSaver(): CrefoPayOmsCommandSaverInterface
    {
        return new CaptureOmsCommandSaver(
            $this->createWriter(),
            $this->createCrefoPayOmsStatusMapper(),
            $this->getConfig()
        );
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

    /**
     * @return \SprykerEco\Service\CrefoPay\CrefoPayServiceInterface
     */
    public function getCrefoPayService(): CrefoPayServiceInterface
    {
        return $this->getProvidedDependency(CrefoPayDependencyProvider::SERVICE_CREFO_PAY);
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface
     */
    public function getUtilTextService(): CrefoPayToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(CrefoPayDependencyProvider::SERVICE_UTIL_TEXT);
    }
}
