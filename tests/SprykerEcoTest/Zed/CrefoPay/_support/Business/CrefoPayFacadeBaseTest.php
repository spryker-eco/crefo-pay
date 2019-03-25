<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\CrefoPay\Business;

use Codeception\TestCase\Test;
use SprykerEco\Service\CrefoPay\CrefoPayServiceInterface;
use SprykerEco\Zed\CrefoPay\Business\CrefoPayBusinessFactory;
use SprykerEco\Zed\CrefoPay\Business\CrefoPayFacade;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCrefoPayApiFacadeBridge;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCrefoPayApiFacadeInterface;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToLocaleFacadeBridge;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToLocaleFacadeInterface;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToOmsFacadeBridge;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToOmsFacadeInterface;
use SprykerEco\Zed\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceBridge;
use SprykerEco\Zed\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface;
use SprykerEco\Zed\CrefoPayApi\Business\CrefoPayApiFacade;
use SprykerEco\Zed\CrefoPayApi\Business\CrefoPayApiFacadeInterface;

class CrefoPayFacadeBaseTest extends Test
{
    /**
     * @var \SprykerEcoTest\Zed\CrefoPay\CrefoPayZedTester
     */
    protected $tester;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\CrefoPayFacadeInterface
     */
    protected $facade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->facade = (new CrefoPayFacade())
            ->setFactory($this->createFactoryMock());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\SprykerEco\Zed\CrefoPay\Business\CrefoPayBusinessFactory
     */
    protected function createFactoryMock(): CrefoPayBusinessFactory
    {
        $builder = $this->getMockBuilder(CrefoPayBusinessFactory::class);
        $builder->setMethods(
            [
                'getConfig',
                'getCrefoPayApiFacade',
                'getLocaleFacade',
                'getOmsFacade',
                'getCrefoPayService',
                'getUtilTextService',
            ]
        );

        $stub = $builder->getMock();
        $stub->method('getConfig')
            ->willReturn($this->tester->createConfig());
        $stub->method('getCrefoPayApiFacade')
            ->willReturn($this->getCrefoPayApiFacade());
        $stub->method('getLocaleFacade')
            ->willReturn($this->getLocaleFacade());
        $stub->method('getOmsFacade')
            ->willReturn($this->getOmsFacade());
        $stub->method('getCrefoPayService')
            ->willReturn($this->getCrefoPayService());
        $stub->method('getUtilTextService')
            ->willReturn($this->getUtilTextService());

        return $stub;
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCrefoPayApiFacadeInterface
     */
    protected function getCrefoPayApiFacade(): CrefoPayToCrefoPayApiFacadeInterface
    {
         return new CrefoPayToCrefoPayApiFacadeBridge($this->createCrefoPayApiFacadeMock());
    }

    /**
     * @return \SprykerEco\Zed\CrefoPayApi\Business\CrefoPayApiFacadeInterface|object
     */
    protected function createCrefoPayApiFacadeMock(): CrefoPayApiFacadeInterface
    {
        return $this->makeEmpty(
            CrefoPayApiFacade::class,
            ['performCreateTransactionApiCall' => $this->tester->createCrefoPayApiResponseTransfer()]
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToLocaleFacadeInterface
     */
    protected function getLocaleFacade(): CrefoPayToLocaleFacadeInterface
    {
        return new CrefoPayToLocaleFacadeBridge($this->tester->getLocator()->locale()->facade());
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToOmsFacadeInterface
     */
    protected function getOmsFacade(): CrefoPayToOmsFacadeInterface
    {
        return new CrefoPayToOmsFacadeBridge($this->tester->getLocator()->oms()->facade());
    }

    /**
     * @return \SprykerEco\Service\CrefoPay\CrefoPayServiceInterface
     */
    protected function getCrefoPayService(): CrefoPayServiceInterface
    {
        return $this->tester->getLocator()->crefoPay()->service();
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface
     */
    protected function getUtilTextService(): CrefoPayToUtilTextServiceInterface
    {
        return new CrefoPayToUtilTextServiceBridge($this->tester->getLocator()->utilText()->service());
    }
}
