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

namespace ImDigital\ServerlessShipping\Model;

use ImDigital\ServerlessShipping\Api\Data\ShippingRateInterface;

class ShippingRate implements ShippingRateInterface
{
    /**
     * @var array
     */
    protected array $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        if (!isset($data[self::FIELD_CODE])) {
            throw new \InvalidArgumentException('Code is required');
        }

        if (!isset($data[self::FIELD_LABEL])) {
            throw new \InvalidArgumentException('Label is required');
        }

        if (!isset($data[self::FIELD_COST])) {
            throw new \InvalidArgumentException('Cost is required');
        }

        if (!isset($data[self::FIELD_PRICE])) {
            throw new \InvalidArgumentException('Price is required');
        }

        $this->data = $data;
    }

    /**
     * @api
     * @return string
     */
    public function getCode(): string
    {
        return (string)$this->data[self::FIELD_CODE];
    }

    /**
     * @api
     * @return string
     */
    public function getLabel(): string
    {
        return (string)$this->data[self::FIELD_LABEL];
    }

    /**
     * @api
     * @return float
     */
    public function getCost(): float
    {
        return (float)$this->data[self::FIELD_COST];
    }

    /**
     * @api
     * @return float
     */
    public function getPrice(): float
    {
        return (float)$this->data[self::FIELD_PRICE];
    }

    /**
     * @api
     * @param string $code
     * @return void
     */
    public function setCode(string $code): void
    {
        $this->data[self::FIELD_CODE] = $code;
    }

    /**
     * @api
     * @param string $label
     * @return void
     */
    public function setLabel(string $label): void
    {
        $this->data[self::FIELD_LABEL] = $label;
    }

    /**
     * @api
     * @param float $cost
     * @return void
     */
    public function setCost(float $cost): void
    {
        $this->data[self::FIELD_COST] = $cost;
    }

    /**
     * @api
     * @param float $price
     * @return void
     */
    public function setPrice(float $price): void
    {
        $this->data[self::FIELD_PRICE] = $price;
    }

    /**
     * @api
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
