<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command;

use Generated\Shared\Transfer\CrefoPayToSalesOrderItemsCollectionTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\CrefoPayOmsCommandTransfer;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder\CrefoPayOmsCommandRequestBuilderInterface;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Client\CrefoPayOmsCommandClientInterface;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\CrefoPayOmsCommandSaverInterface;
use SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToOmsFacadeInterface;

class CrefoPayOmsCommand implements CrefoPayOmsCommandInterface
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
     * @var \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Client\CrefoPayOmsCommandClientInterface
     */
    protected $omsCommandClient;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\CrefoPayOmsCommandSaverInterface
     */
    protected $saver;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder\CrefoPayOmsCommandRequestBuilderInterface $requestBuilder
     * @param \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface $reader
     * @param \SprykerEco\Zed\CrefoPay\Business\Oms\Command\Client\CrefoPayOmsCommandClientInterface $omsCommandClient
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
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemsCollectionTransfer $crefoPayToSalesOrderItemsCollection
     *
     * @return void
     */
    public function execute(
        OrderTransfer $orderTransfer,
        CrefoPayToSalesOrderItemsCollectionTransfer $crefoPayToSalesOrderItemsCollection
    ): void {
        $crefoPayOmsCommandTransfer = $this->createCrefoPayOmsCommandTransfer(
            $orderTransfer,
            $crefoPayToSalesOrderItemsCollection
        );

        $requestTransfer = $this->requestBuilder
            ->buildRequestTransfer($orderTransfer, $crefoPayOmsCommandTransfer);
        $responseTransfer = $this->omsCommandClient->performApiCall($requestTransfer);

        $crefoPayOmsCommandTransfer
            ->setRequest($requestTransfer)
            ->setResponse($responseTransfer);

        $this->saver->savePaymentEntities($crefoPayOmsCommandTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemsCollectionTransfer $crefoPayToSalesOrderItemsCollection
     *
     * @return \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer
     */
    protected function createCrefoPayOmsCommandTransfer(
        OrderTransfer $orderTransfer,
        CrefoPayToSalesOrderItemsCollectionTransfer $crefoPayToSalesOrderItemsCollection
    ): CrefoPayOmsCommandTransfer {
        $paymentCrefoPayTransfer = $this->reader
            ->findPaymentCrefoPayByFkSalesOrder($orderTransfer->getIdSalesOrder());

        return (new CrefoPayOmsCommandTransfer)
            ->setPaymentCrefoPay($paymentCrefoPayTransfer)
            ->setCrefoPayToSalesOrderItemsCollection($crefoPayToSalesOrderItemsCollection);
    }
}
