<?php
/**
 * Codifi_CustomerRequest
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\CustomerRequest\Logger;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;

/**
 * Class Handler
 * @package Codifi\Training\Logger
 */
class Handler extends Base
{
    /**
     * Logging level
     *
     * @var int
     */
    protected $loggerType = Logger::ERROR;

    /**
     * File name
     *
     * @var string
     */
    protected $fileName = '/var/log/saverequestnote.log';
}
