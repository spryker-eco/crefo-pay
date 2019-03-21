<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\CrefoPay\Business;

use Codeception\TestCase\Test;
use SprykerEco\Zed\CrefoPay\Business\CrefoPayBusinessFactory;
use SprykerEco\Zed\CrefoPay\Business\CrefoPayFacade;

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
            ]
        );

        $stub = $builder->getMock();
        $stub->method('getConfig')
            ->willReturn($this->tester->createConfig());

        return $stub;
    }
}
