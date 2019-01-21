<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Dependency\Service;

class CrefoPayToUtilEncodingServiceBridge implements CrefoPayToUtilEncodingServiceInterface
{
    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $encodingService;

    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $encodingService
     */
    public function __construct($encodingService)
    {
        $this->encodingService = $encodingService;
    }

    /**
     * @param string $jsonValue
     * @param bool $assoc
     * @param int|null $depth
     * @param int|null $options
     *
     * @return mixed|null
     */
    public function decodeJson($jsonValue, $assoc = false, $depth = null, $options = null)
    {
        return $this->encodingService->decodeJson($jsonValue, $assoc, $depth, $options);
    }
}
