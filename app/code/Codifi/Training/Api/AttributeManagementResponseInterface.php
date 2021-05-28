<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Training\Api;

/**
 * Interface AttributeManagementResponseInterface
 * @package Codifi\Training\Api
 */
interface AttributeManagementResponseInterface
{
    /**
     * Successful response status
     *
     * @var string
     */
    const STATUS_OK = 'OK';

    /**
     * Failed response status
     *
     * @var string
     */
    const STATUS_FAILED = 'Failed';

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus();

    /**
     * Set status
     *
     * @param string $status
     * @return void
     */
    public function setStatus(string $status);

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage();

    /**
     * Set message
     *
     * @param string $message
     * @return void
     */
    public function setMessage(string $message);
}
