<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Yves\CrefoPay\Quote;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CrefoPayApiAddressTransfer;
use Generated\Shared\Transfer\CrefoPayTransactionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Client\CrefoPay\CrefoPayClientInterface;
use SprykerEco\Yves\CrefoPay\Quote\CrefoPayQuoteExpander;
use SprykerEco\Yves\CrefoPay\Quote\CrefoPayQuoteExpanderInterface;
use SprykerEcoTest\Yves\CrefoPay\CrefoPayYvesTester;

/**
 * @group Functional
 * @group SprykerEco
 * @group Yves
 * @group CrefoPay
 * @group Quote
 * @group CrefoPayQuoteExpanderTest
 * Add your own group annotations below this line
 */
class CrefoPayQuoteExpanderTest extends Unit
{
    /**
     * @var string
     */
    protected const COUNTRY_CODE_DE = 'DE';

    /**
     * @var string
     */
    protected const COUNTRY_CODE_US = 'US';

    /**
     * @var \SprykerEcoTest\Yves\CrefoPay\CrefoPayYvesTester
     */
    protected CrefoPayYvesTester $tester;

    /**
     * @return void
     */
    public function testExpandShouldExecuteClientCallWhenCrefoPayTransactionIsNotSet(): void
    {
        // Arrange
        $clientMock = $this->createClientMock();
        $clientMock->expects($this->once())->method('startCrefoPayTransaction');
        $crefoPayQuoteExpander = $this->createCrefoPayQuoteExpander($clientMock);

        // Act
        $crefoPayQuoteExpander->expand($this->tester->getRequest(), new QuoteTransfer());
    }

    /**
     * @return void
     */
    public function testExpandShouldExecuteClientCallWhenCrefoPayTransactionIsNotSuccess(): void
    {
        // Arrange
        $clientMock = $this->createClientMock();
        $clientMock->expects($this->once())->method('startCrefoPayTransaction');
        $crefoPayQuoteExpander = $this->createCrefoPayQuoteExpander($clientMock);

        $quoteTransfer = (new QuoteTransfer())->setCrefoPayTransaction(new CrefoPayTransactionTransfer());

        // Act
        $crefoPayQuoteExpander->expand($this->tester->getRequest(), $quoteTransfer);
    }

    /**
     * @return void
     */
    public function testExpandShouldExecuteClientCallWhenCountriesAreNotProvided(): void
    {
        // Arrange
        $clientMock = $this->createClientMock();
        $clientMock->expects($this->once())->method('startCrefoPayTransaction');
        $crefoPayQuoteExpander = $this->createCrefoPayQuoteExpander($clientMock);

        $quoteTransfer = (new QuoteTransfer())
            ->setCrefoPayTransaction((new CrefoPayTransactionTransfer())->setIsSuccess(true));

        // Act
        $crefoPayQuoteExpander->expand($this->tester->getRequest(), $quoteTransfer);
    }

    /**
     * @return void
     */
    public function testExpandShouldExecuteClientCallWhenCountriesAreNotEqual(): void
    {
        // Arrange
        $clientMock = $this->createClientMock();
        $clientMock->expects($this->once())->method('startCrefoPayTransaction');
        $crefoPayQuoteExpander = $this->createCrefoPayQuoteExpander($clientMock);

        $crefoPayTransactionTransfer = (new CrefoPayTransactionTransfer())
            ->setIsSuccess(true)
            ->setBillingAddress((new CrefoPayApiAddressTransfer())->setCountry(static::COUNTRY_CODE_DE));

        $quoteTransfer = (new QuoteTransfer())
            ->setCrefoPayTransaction($crefoPayTransactionTransfer)
            ->setBillingAddress((new AddressTransfer())->setIso2Code(static::COUNTRY_CODE_US));

        // Act
        $crefoPayQuoteExpander->expand($this->tester->getRequest(), $quoteTransfer);
    }

    /**
     * @return void
     */
    public function testExpandShouldNotExecuteClientCallWhenCountriesAreEqual(): void
    {
        // Arrange
        $clientMock = $this->createClientMock();
        $clientMock->expects($this->never())->method('startCrefoPayTransaction');
        $crefoPayQuoteExpander = $this->createCrefoPayQuoteExpander($clientMock);

        $crefoPayTransactionTransfer = (new CrefoPayTransactionTransfer())
            ->setIsSuccess(true)
            ->setBillingAddress((new CrefoPayApiAddressTransfer())->setCountry(static::COUNTRY_CODE_DE));

        $quoteTransfer = (new QuoteTransfer())
            ->setCrefoPayTransaction($crefoPayTransactionTransfer)
            ->setBillingAddress((new AddressTransfer())->setIso2Code(static::COUNTRY_CODE_DE));

        // Act
        $crefoPayQuoteExpander->expand($this->tester->getRequest(), $quoteTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Client\CrefoPay\CrefoPayClientInterface
     */
    protected function createClientMock(): CrefoPayClientInterface
    {
        return $this->getMockBuilder(CrefoPayClientInterface::class)->getMock();
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Client\CrefoPay\CrefoPayClientInterface $clientMock
     *
     * @return \SprykerEco\Yves\CrefoPay\Quote\CrefoPayQuoteExpanderInterface
     */
    protected function createCrefoPayQuoteExpander(CrefoPayClientInterface $clientMock): CrefoPayQuoteExpanderInterface
    {
        return new CrefoPayQuoteExpander($clientMock);
    }
}
