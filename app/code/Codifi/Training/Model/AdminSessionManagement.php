<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Training\Model;

use Magento\Backend\Model\Auth\Session;

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
     * Auth session
     *
     * @var Session
     */
    private $authSession;

    /**
     * AdminSessionManagement constructor.
     *
     * @param Session $authSession
     */
    public function __construct(
        Session $authSession
    ) {
        $this->authSession = $authSession;
    }

    /**
     * Get admin id
     *
     * @return int
     */
    public function getAdminId(): int
    {
        $admin = $this->authSession->getUser();

        return (int)$admin->getId() ?? 0;
    }
}
