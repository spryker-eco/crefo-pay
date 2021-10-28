<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command;

use Generated\Shared\Transfer\CrefoPayOmsCommandTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder\CrefoPayOmsCommandRequestBuilderInterface;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\CommandClient\CrefoPayOmsCommandClientInterface;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\CrefoPayOmsCommandSaverInterface;
use SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface;

class CrefoPayOmsCommandByOrder implements CrefoPayOmsCommandInterface
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
     * @var \SprykerEco\Zed\CrefoPay\Business\Oms\Command\CommandClient\CrefoPayOmsCommandClientInterface
     */
    protected $omsCommandClient;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\CrefoPayOmsCommandSaverInterface
     */
    protected $saver;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder\CrefoPayOmsCommandRequestBuilderInterface $requestBuilder
     * @param \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface $reader
     * @param \SprykerEco\Zed\CrefoPay\Business\Oms\Command\CommandClient\CrefoPayOmsCommandClientInterface $omsCommandClient
     * @param \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\CrefoPayOmsCommandSaverInterface $saver
     */
    public function __construct(
        CrefoPayOmsCommandRequestBuilderInterface $requestBuilder,
        CrefoPayReaderInterface $reader,
        CrefoPayOmsCommandClientInterface $omsCommandClient,
        CrefoPayOmsCommandSaverInterface $saver
    ) {
        $this->requestBuilder = $requestBuilder;
        $this->reader = $reader;
        $this->omsCommandClient = $omsCommandClient;
        $this->saver = $saver;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<int> $salesOrderItemIds
     *
     * @return void
     */
    public function execute(OrderTransfer $orderTransfer, array $salesOrderItemIds): void
    {
        $crefoPayOmsCommandTransfer = $this->createCrefoPayOmsCommandTransfer(
            $orderTransfer,
            $salesOrderItemIds,
        );
        $crefoPayOmsCommandTransfer = $this->requestBuilder
            ->buildRequestTransfer($crefoPayOmsCommandTransfer);
        $crefoPayOmsCommandTransfer = $this->performApiCall($crefoPayOmsCommandTransfer);
        $this->saver->savePaymentEntities($crefoPayOmsCommandTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<int> $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer
     */
    protected function createCrefoPayOmsCommandTransfer(
        OrderTransfer $orderTransfer,
        array $salesOrderItemIds
    ): CrefoPayOmsCommandTransfer {
        $paymentCrefoPayTransfer = $this->reader
            ->getPaymentCrefoPayByIdSalesOrder($orderTransfer->getIdSalesOrder());

        $paymentCrefoPayOrderItemCollection = $this->reader
            ->getPaymentCrefoPayOrderItemCollectionBySalesOrderItemIds($salesOrderItemIds);

        return (new CrefoPayOmsCommandTransfer())
            ->setOrder($orderTransfer)
            ->setPaymentCrefoPay($paymentCrefoPayTransfer)
            ->setPaymentCrefoPayOrderItemCollection($paymentCrefoPayOrderItemCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer
     */
    protected function performApiCall(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): CrefoPayOmsCommandTransfer
    {
        $responseTransfer = $this->omsCommandClient
            ->performApiCall($crefoPayOmsCommandTransfer->getRequest());

        $crefoPayOmsCommandTransfer->setResponse($responseTransfer);

        return $crefoPayOmsCommandTransfer;
    }
}
