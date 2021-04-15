<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Training\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class ConfigProvider
 * @package Codifi\Training\Model
 */
class ConfigProvider
{
    /**
     * Path to the system option Enabled - Credit Hold.
     *
     * @var string
     */
    const PATH_OPTION_ENABLE = 'codifi/credit_hold/active';

    /**
     * Path to the system option Value - Message Credit Hold.
     *
     * @var string
     */
    const PATH_OPTION_MESSAGE = 'codifi/credit_hold/message';

    /**
     * Name of session attribute for once show popup.
     *
     * @var string
     */
    const SESSION_FLAG = 'flag';

    /**
     * Scope config.
     *
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * ConfigProvider constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Is option credit hold enabled.
     *
     * @return bool
     */
    public function isOptionCreditHoldEnable() : bool
    {
        return $this->scopeConfig->isSetFlag(self::PATH_OPTION_ENABLE, ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage() : string
    {
        return $this->scopeConfig->getValue(self::PATH_OPTION_MESSAGE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }
}
