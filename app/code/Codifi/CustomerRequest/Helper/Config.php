<?php
/**
 * Codifi_CustomerRequest
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\CustomerRequest\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class Config
 * @package Codifi\CustomerRequest\Helper
 */
class Config extends AbstractHelper
{
    /**
     * Path to config
     */
    const MONTHS_PERIOD_PATH = 'customer_note/archive/lifetime';

    /**
     * Get months period
     *
     * @return int
     */
    public function getPeriod(): int
    {
        return (int)$this->scopeConfig->getValue(self::MONTHS_PERIOD_PATH, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }
}
