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
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder\CancelOmsCommandRequestBuilder;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder\CaptureOmsCommandRequestBuilder;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder\CrefoPayOmsCommandRequestBuilderInterface;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder\FinishOmsCommandRequestBuilder;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder\RefundOmsCommandRequestBuilder;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Client\CancelOmsCommandClient;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Client\CaptureOmsCommandClient;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Client\CrefoPayOmsCommandClientInterface;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Client\FinishOmsCommandClient;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Client\RefundOmsCommandClient;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\CrefoPayOmsCommandByItem;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\CrefoPayOmsCommandByItemInterface;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\CrefoPayOmsCommandByOrder;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\CrefoPayOmsCommandByOrderInterface;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\CancelOmsCommandSaver;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\CaptureOmsCommandSaver;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\CrefoPayOmsCommandSaverInterface;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\FinishOmsCommandSaver;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\RefundOmsCommandSaver;
use SprykerEco\Zed\CrefoPay\Business\Oms\Condition\CrefoPayOmsConditionInterface;
use SprykerEco\Zed\CrefoPay\Business\Oms\Condition\IsAuthorizedOmsCondition;
use SprykerEco\Zed\CrefoPay\Business\Oms\Condition\IsCanceledOmsCondition;
use SprykerEco\Zed\CrefoPay\Business\Oms\Condition\IsCancellationPendingOmsCondition;
use SprykerEco\Zed\CrefoPay\Business\Oms\Condition\IsCapturedOmsCondition;
use SprykerEco\Zed\CrefoPay\Business\Oms\Condition\IsCapturePendingOmsCondition;
use SprykerEco\Zed\CrefoPay\Business\Oms\Condition\IsDoneOmsCondition;
use SprykerEco\Zed\CrefoPay\Business\Oms\Condition\IsExpiredOmsCondition;
use SprykerEco\Zed\CrefoPay\Business\Oms\Condition\IsFinishedOmsCondition;
use SprykerEco\Zed\CrefoPay\Business\Oms\Condition\IsReservedOmsCondition;
use SprykerEco\Zed\CrefoPay\Business\Oms\Condition\IsWaitingForCaptureOmsCondition;
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
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToOmsFacadeInterface;
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
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Command\CrefoPayOmsCommandByItemInterface
     */
    public function createCaptureOmsCommand(): CrefoPayOmsCommandByItemInterface
    {
        return new CrefoPayOmsCommandByItem(
            $this->createCaptureOmsCommandRequestBuilder(),
            $this->createReader(),
            $this->createCaptureOmsCommandClient(),
            $this->createCaptureOmsCommandSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Command\CrefoPayOmsCommandByOrderInterface
     */
    public function createCancelOmsCommand(): CrefoPayOmsCommandByOrderInterface
    {
        return new CrefoPayOmsCommandByOrder(
            $this->createCancelOmsCommandRequestBuilder(),
            $this->createReader(),
            $this->createCancelOmsCommandClient(),
            $this->createCancelOmsCommandSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Command\CrefoPayOmsCommandByItemInterface
     */
    public function createRefundOmsCommand(): CrefoPayOmsCommandByItemInterface
    {
        return new CrefoPayOmsCommandByItem(
            $this->createRefundOmsCommandRequestBuilder(),
            $this->createReader(),
            $this->createRefundOmsCommandClient(),
            $this->createRefundOmsCommandSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Command\CrefoPayOmsCommandByOrderInterface
     */
    public function createFinishOmsCommand(): CrefoPayOmsCommandByOrderInterface
    {
        return new CrefoPayOmsCommandByOrder(
            $this->createFinishOmsCommandRequestBuilder(),
            $this->createReader(),
            $this->createFinishOmsCommandClient(),
            $this->createFinishOmsCommandSaver()
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
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder\CrefoPayOmsCommandRequestBuilderInterface
     */
    public function createCancelOmsCommandRequestBuilder(): CrefoPayOmsCommandRequestBuilderInterface
    {
        return new CancelOmsCommandRequestBuilder($this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder\CrefoPayOmsCommandRequestBuilderInterface
     */
    public function createRefundOmsCommandRequestBuilder(): CrefoPayOmsCommandRequestBuilderInterface
    {
        return new RefundOmsCommandRequestBuilder($this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder\CrefoPayOmsCommandRequestBuilderInterface
     */
    public function createFinishOmsCommandRequestBuilder(): CrefoPayOmsCommandRequestBuilderInterface
    {
        return new FinishOmsCommandRequestBuilder($this->getConfig());
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Client\CrefoPayOmsCommandClientInterface
     */
    public function createCaptureOmsCommandClient(): CrefoPayOmsCommandClientInterface
    {
        return new CaptureOmsCommandClient($this->getCrefoPayApiFacade());
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Client\CrefoPayOmsCommandClientInterface
     */
    public function createCancelOmsCommandClient(): CrefoPayOmsCommandClientInterface
    {
        return new CancelOmsCommandClient($this->getCrefoPayApiFacade());
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Client\CrefoPayOmsCommandClientInterface
     */
    public function createRefundOmsCommandClient(): CrefoPayOmsCommandClientInterface
    {
        return new RefundOmsCommandClient($this->getCrefoPayApiFacade());
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Client\CrefoPayOmsCommandClientInterface
     */
    public function createFinishOmsCommandClient(): CrefoPayOmsCommandClientInterface
    {
        return new FinishOmsCommandClient($this->getCrefoPayApiFacade());
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\CrefoPayOmsCommandSaverInterface
     */
    public function createCaptureOmsCommandSaver(): CrefoPayOmsCommandSaverInterface
    {
        return new CaptureOmsCommandSaver(
            $this->createReader(),
            $this->createWriter(),
            $this->getConfig(),
            $this->getOmsFacade(),
            $this->createCrefoPayOmsStatusMapper()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\CrefoPayOmsCommandSaverInterface
     */
    public function createCancelOmsCommandSaver(): CrefoPayOmsCommandSaverInterface
    {
        return new CancelOmsCommandSaver(
            $this->createReader(),
            $this->createWriter(),
            $this->getConfig(),
            $this->getOmsFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\CrefoPayOmsCommandSaverInterface
     */
    public function createRefundOmsCommandSaver(): CrefoPayOmsCommandSaverInterface
    {
        return new RefundOmsCommandSaver(
            $this->createReader(),
            $this->createWriter(),
            $this->getConfig(),
            $this->getOmsFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\CrefoPayOmsCommandSaverInterface
     */
    public function createFinishOmsCommandSaver(): CrefoPayOmsCommandSaverInterface
    {
        return new FinishOmsCommandSaver(
            $this->createReader(),
            $this->createWriter(),
            $this->getConfig(),
            $this->getOmsFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Condition\CrefoPayOmsConditionInterface
     */
    public function createIsReservedOmsCondition(): CrefoPayOmsConditionInterface
    {
        return new IsReservedOmsCondition($this->createReader());
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Condition\CrefoPayOmsConditionInterface
     */
    public function createIsAuthorizedOmsCondition(): CrefoPayOmsConditionInterface
    {
        return new IsAuthorizedOmsCondition(
            $this->createReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Condition\CrefoPayOmsConditionInterface
     */
    public function createIsWaitingForCaptureOmsCondition(): CrefoPayOmsConditionInterface
    {
        return new IsWaitingForCaptureOmsCondition(
            $this->createReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Condition\CrefoPayOmsConditionInterface
     */
    public function createIsCancellationPendingOmsCondition(): CrefoPayOmsConditionInterface
    {
        return new IsCancellationPendingOmsCondition($this->createReader());
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Condition\CrefoPayOmsConditionInterface
     */
    public function createIsCanceledOmsCondition(): CrefoPayOmsConditionInterface
    {
        return new IsCanceledOmsCondition(
            $this->createReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Condition\CrefoPayOmsConditionInterface
     */
    public function createIsExpiredOmsCondition(): CrefoPayOmsConditionInterface
    {
        return new IsExpiredOmsCondition(
            $this->createReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Condition\CrefoPayOmsConditionInterface
     */
    public function createIsCapturePendingOmsCondition(): CrefoPayOmsConditionInterface
    {
        return new IsCapturePendingOmsCondition($this->createReader());
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Condition\CrefoPayOmsConditionInterface
     */
    public function createIsCapturedOmsCondition(): CrefoPayOmsConditionInterface
    {
        return new IsCapturedOmsCondition(
            $this->createReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Condition\CrefoPayOmsConditionInterface
     */
    public function createIsFinishedOmsCondition(): CrefoPayOmsConditionInterface
    {
        return new IsFinishedOmsCondition($this->createReader());
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\Oms\Condition\CrefoPayOmsConditionInterface
     */
    public function createIsDoneOmsCondition(): CrefoPayOmsConditionInterface
    {
        return new IsDoneOmsCondition(
            $this->createReader(),
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
     * @return \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToOmsFacadeInterface
     */
    public function getOmsFacade(): CrefoPayToOmsFacadeInterface
    {
        return $this->getProvidedDependency(CrefoPayDependencyProvider::FACADE_OMS);
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
