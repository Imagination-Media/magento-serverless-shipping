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

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(ComponentRegistrar::MODULE, 'ImDigital_ServerlessShipping', __DIR__);
