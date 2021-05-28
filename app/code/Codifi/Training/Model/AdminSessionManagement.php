<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Training\Model;

use Magento\Backend\Model\Session as BackendSession;

/**
 * Class AdminSessionManagement
 * @package Codifi\Training\Model
 */
class AdminSessionManagement
{
    /**
     * Admin session attribute customers id.
     */
    const ADMIN_SESSION_ATTRIBUTE_CUSTOMER_IDS = 'customers_id';

    /**
     * Backend session.
     *
     * @var BackendSession
     */
    private $backendSession;

    /**
     * AdminSessionManagement constructor.
     *
     * @param BackendSession $backendSession
     */
    public function __construct(
        BackendSession $backendSession
    ) {
        $this->backendSession = $backendSession;
    }

    /**
     * Get current customer id from admin session.
     *
     * @return int
     */
    public function getCustomerId(): int
    {
        $customerData = $this->backendSession->getCustomerData();

        return (int)$customerData['account']['id'] ?? 0;
    }
}
