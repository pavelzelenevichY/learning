<?php
/**
 * Codifi_CustomerRequest
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\CustomerRequest\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Config
 * @package Codifi\CustomerRequest\Helper
 */
class Config extends AbstractHelper
{
    /**
     * Success response message after customer request
     */
    const SUCCESS_MESSAGE = "Thanks for contacting us with your request. We'll respond to you very soon.";

    /**
     * Error response message after customer request
     */
    const ERROR_MESSAGE = "An error occurred while processing your form. Please try again later";
}
