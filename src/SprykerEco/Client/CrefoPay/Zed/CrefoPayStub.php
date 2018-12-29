<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\CrefoPay\Zed;

use SprykerEco\Client\CrefoPay\Dependency\Client\CrefoPayToZedRequestClientInterface;

class CrefoPayStub implements CrefoPayStubInterface
{
    /**
     * @var \SprykerEco\Client\CrefoPay\Dependency\Client\CrefoPayToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \SprykerEco\Client\CrefoPay\Dependency\Client\CrefoPayToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(CrefoPayToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }
}
