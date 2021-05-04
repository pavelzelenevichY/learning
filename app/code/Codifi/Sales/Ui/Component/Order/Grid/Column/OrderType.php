<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Ui\Component\Order\Grid\Column;

use Magento\Framework\Data\OptionSourceInterface;
use Codifi\Sales\Helper\Config;

/**
 * Class OrderType
 * @package Codifi\Sales\Ui\Component\Order\Grid\Column
 */
class OrderType implements OptionSourceInterface
{
    /**
     * Config
     *
     * @var Config
     */
    private $config;

    /**
     * OrderType constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Get option order_type
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $options = $this->config->getAllOptionsOrderType();

        return $options;
    }
}
