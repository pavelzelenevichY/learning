<?php
/**
 * Codifi_CustomerRequest
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\CustomerRequest\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;

class CreditHoldSection implements SectionSourceInterface
{
    /**
     * Get data
     *
     * @return array
     */
    public function getSectionData()
    {
        return [
            'customdata' => "1"
        ];
    }
}
