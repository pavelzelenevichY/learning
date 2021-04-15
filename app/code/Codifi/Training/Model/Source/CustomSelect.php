<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Training\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class CustomSelect
 * @package Codifi\Training\Model\Source
 */
class CustomSelect extends AbstractSource
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
                ['label' => __('Yes'), 'value' => 1],
                ['label' => __('No'), 'value' => 0],
            ];
        }

        return $this->_options;
    }
}
