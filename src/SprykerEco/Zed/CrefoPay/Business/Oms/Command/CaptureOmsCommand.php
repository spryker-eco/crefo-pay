<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command;

use Generated\Shared\Transfer\CrefoPayToSalesOrderItemCollectionTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SaveCrefoPayEntitiesTransfer;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder\CrefoPayOmsCommandRequestBuilderInterface;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\CrefoPayOmsCommandSaverInterface;
use SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCrefoPayApiFacadeInterface;

class CaptureOmsCommand implements CrefoPayOmsCommandInterface
{
    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder\CrefoPayOmsCommandRequestBuilderInterface
     */
    protected $requestBuilder;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface
     */
    protected $reader;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\CrefoPayOmsCommandSaverInterface
     */
    protected $saver;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCrefoPayApiFacadeInterface
     */
    protected $crefoPayApiFacade;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder\CrefoPayOmsCommandRequestBuilderInterface $requestBuilder
     * @param \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface $reader
     * @param \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\CrefoPayOmsCommandSaverInterface $saver
     * @param \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCrefoPayApiFacadeInterface $crefoPayApiFacade
     */
    public function __construct(
        CrefoPayOmsCommandRequestBuilderInterface $requestBuilder,
        CrefoPayReaderInterface $reader,
        CrefoPayOmsCommandSaverInterface $saver,
        CrefoPayToCrefoPayApiFacadeInterface $crefoPayApiFacade
    ) {
        $this->requestBuilder = $requestBuilder;
        $this->reader = $reader;
        $this->saver = $saver;
        $this->crefoPayApiFacade = $crefoPayApiFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemCollectionTransfer $crefoPayToSalesOrderItemCollectionTransfer
     *
     * @return void
     */
    public function execute(
        OrderTransfer $orderTransfer,
        CrefoPayToSalesOrderItemCollectionTransfer $crefoPayToSalesOrderItemCollectionTransfer
    ): void {
        $saveCrefoPayEntitiesTransfer = $this->createSaveCrefoPayEntitiesTransfer(
            $orderTransfer,
            $crefoPayToSalesOrderItemCollectionTransfer
        );

        $saveCrefoPayEntitiesTransfer->setRequest(
            $this->requestBuilder
                ->buildRequestTransfer(
                    $saveCrefoPayEntitiesTransfer->getPaymentCrefoPay(),
                    $orderTransfer,
                    $crefoPayToSalesOrderItemCollectionTransfer
                )
        );

        $saveCrefoPayEntitiesTransfer->setResponse(
            $this->crefoPayApiFacade->performCaptureApiCall($saveCrefoPayEntitiesTransfer->getRequest())
        );

        $this->saver->savePaymentEntities($saveCrefoPayEntitiesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemCollectionTransfer $crefoPayToSalesOrderItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SaveCrefoPayEntitiesTransfer
     */
    protected function createSaveCrefoPayEntitiesTransfer(
        OrderTransfer $orderTransfer,
        CrefoPayToSalesOrderItemCollectionTransfer $crefoPayToSalesOrderItemCollectionTransfer
    ): SaveCrefoPayEntitiesTransfer {
        $paymentCrefoPayTransfer = $this->reader
            ->findPaymentCrefoPayByFkSalesOrder($orderTransfer->getIdSalesOrder());

        $paymentCrefoPayOrderItemCollectionTransfer = $this->reader
            ->findPaymentCrefoPayOrderItemsByCrefoPayToSalesOrderItemCollection(
                $crefoPayToSalesOrderItemCollectionTransfer
            );

        return (new SaveCrefoPayEntitiesTransfer)
            ->setPaymentCrefoPay($paymentCrefoPayTransfer)
            ->setPaymentCrefoPayOrderItemCollection($paymentCrefoPayOrderItemCollectionTransfer);
    }
}
