<?php

/**
 * Serverless Shipping
 *
 * Calculate shipping rates using a serverless function.
 *
 * @package ImDigital\ServerlessShipping
 * @author Igor Ludgero Miura <igor@imdigital.com>
 * @copyright Copyright (c) 2023 Imagination Media (https://www.imdigital.com/)
 * @license Private
 */

declare(strict_types=1);

namespace ImDigital\ServerlessShipping\Model\Config\Source;

use ImDigital\Serverless\Api\ServerlessFunctionInterface;
use ImDigital\Serverless\Api\ServerlessFunctionRepositoryInterface;
use Magento\Framework\Data\OptionSourceInterface;

class ServerlessFunction implements OptionSourceInterface
{
    /**
     * @var ServerlessFunctionRepositoryInterface
     */
    protected ServerlessFunctionRepositoryInterface $serverlessRepository;

    /**
     * @param ServerlessFunctionRepositoryInterface $serverlessRepository
     */
    public function __construct(
        ServerlessFunctionRepositoryInterface $serverlessRepository
    ) {
        $this->serverlessRepository = $serverlessRepository;
    }

    /**
     * Get available functions
     * @return array
     */
    public function toOptionArray(): array
    {
        $items = [];
        $serverlessFunctions = $this->serverlessRepository->getFunctionsByEvent();

        /**
         * @var ServerlessFunctionInterface $serverlessFunction
         */
        foreach ($serverlessFunctions as $serverlessFunction) {
            $items[] = [
                'value' => $serverlessFunction->getId(),
                'label' => $serverlessFunction->getName(),
            ];
        }

        return $items;
    }
}
