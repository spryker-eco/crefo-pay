<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command;

use Generated\Shared\Transfer\CrefoPayOmsCommandTransfer;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder\CrefoPayOmsCommandRequestBuilderInterface;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Client\CrefoPayOmsCommandClientInterface;
use SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver\CrefoPayOmsCommandSaverInterface;
use SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface;

class CrefoPayOmsCommandByItem implements CrefoPayOmsCommandByItemInterface
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
     * @param int $idSalesOrderItem
     *
     * @return void
     */
    public function execute(int $idSalesOrderItem): void
    {
        $crefoPayOmsCommandTransfer = $this->createCrefoPayOmsCommandTransfer($idSalesOrderItem);

        $requestTransfer = $this->requestBuilder
            ->buildRequestTransfer($crefoPayOmsCommandTransfer);
        $responseTransfer = $this->omsCommandClient->performApiCall($requestTransfer);

        $crefoPayOmsCommandTransfer
            ->setRequest($requestTransfer)
            ->setResponse($responseTransfer);

        $this->saver->savePaymentEntities($crefoPayOmsCommandTransfer);
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer
     */
    protected function createCrefoPayOmsCommandTransfer(int $idSalesOrderItem): CrefoPayOmsCommandTransfer
    {
        $paymentCrefoPayTransfer = $this->reader
            ->findPaymentCrefoPayByIdSalesOrderItem($idSalesOrderItem);

        $paymentCrefoPayOrderItemCollection = $this->reader
            ->findPaymentCrefoPayOrderItemsBySalesOrderItemIds([$idSalesOrderItem]);

        return (new CrefoPayOmsCommandTransfer)
            ->setPaymentCrefoPay($paymentCrefoPayTransfer)
            ->setPaymentCrefoPayOrderItemCollection($paymentCrefoPayOrderItemCollection);
    }
}
