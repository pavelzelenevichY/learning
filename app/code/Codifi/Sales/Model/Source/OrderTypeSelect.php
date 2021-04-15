<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class OrderTypeSelect
 * @package Codifi\Sales\Model\Source
 */
class OrderTypeSelect extends AbstractSource
{
    /**
     * Get all options.
     *
     * @return array
     */
    public function getAllOptions() : array
    {
        if (null === $this->_options) {
            $this->_options = [
                ['label' => __('Regular Order'), 'value' => 'REGULAR'],
                ['label' => __('Credit Hold Order'), 'value' => 'CREDIT_HOLD']
            ];
        }

        return $this->_options;
    }
}
