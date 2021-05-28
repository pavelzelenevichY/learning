<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Training\Model;

use Codifi\Training\Api\AttributeManagementResponseInterface;

/**
 * Class AttributeManagementResponse
 * @package Codifi\Training\Model
 */
class AttributeManagementResponse implements AttributeManagementResponseInterface
{
    /**
     * Status
     *
     * @var string $status
     */
    private $status;

    /**
     * Message
     *
     * @var string $message
     */
    private $message;

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Set message
     *
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
