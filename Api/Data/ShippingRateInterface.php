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

namespace ImDigital\ServerlessShipping\Api\Data;

interface ShippingRateInterface
{
    public const FIELD_CODE  = 'code';
    public const FIELD_LABEL = 'label';
    public const FIELD_COST  = 'cost';
    public const FIELD_PRICE = 'price';

    /**
     * @api
     * @return string
     */
    public function getCode(): string;

    /**
     * @api
     * @return string
     */
    public function getLabel(): string;

    /**
     * @api
     * @return float
     */
    public function getCost(): float;

    /**
     * @api
     * @return float
     */
    public function getPrice(): float;

    /**
     * @api
     * @param string $code
     * @return void
     */
    public function setCode(string $code): void;

    /**
     * @api
     * @param string $label
     * @return void
     */
    public function setLabel(string $label): void;

    /**
     * @api
     * @param float $cost
     * @return void
     */
    public function setCost(float $cost): void;

    /**
     * @api
     * @param float $price
     * @return void
     */
    public function setPrice(float $price): void;

    /**
     * @api
     * @return array
     */
    public function getData(): array;
}
