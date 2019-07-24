<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Dependency\Service;

class CrefoPayToCrefoPayApiServiceBridge implements CrefoPayToCrefoPayApiServiceInterface
{
    /**
     * @var \SprykerEco\Service\CrefoPayApi\CrefoPayApiServiceInterface
     */
    protected $crefoPayApiService;

    /**
     * @param \SprykerEco\Service\CrefoPayApi\CrefoPayApiServiceInterface $crefoPayApiService
     */
    public function __construct($crefoPayApiService)
    {
        $this->crefoPayApiService = $crefoPayApiService;
    }

    /**
     * @param array $data
     * @param string $mac
     *
     * @return bool
     */
    public function validateMac(array $data, string $mac): bool
    {
        return $this->crefoPayApiService->validateMac($data, $mac);
    }
}
