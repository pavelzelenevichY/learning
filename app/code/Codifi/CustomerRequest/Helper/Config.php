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
    const MONTHS_PERIOD_PATH = 'request/archive/months';

    /**
     * Config constructor.
     *
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    /**
     * Get months period
     *
     * @return int
     */
    public function getPeriod(): int
    {
        return $this->scopeConfig->getValue(self::MONTHS_PERIOD_PATH, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }
}
