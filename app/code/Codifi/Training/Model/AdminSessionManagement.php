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
use Magento\Backend\Model\Session as BackendSession;
use Codifi\Training\Setup\Patch\Data\AddCustomerAttributeCreditHold;

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
     * Auth session.
     *
     * @var Session
     */
    private $authSession;

    /**
     * Backend session.
     *
     * @var BackendSession
     */
    private $backendSession;

    /**
     * Config provider.
     *
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * AdminSessionManagement constructor.
     *
     * @param ConfigProvider $configProvider
     * @param Session $authSession
     * @param BackendSession $backendSession
     */
    public function __construct(
        ConfigProvider $configProvider,
        Session $authSession,
        BackendSession $backendSession
    ) {
        $this->configProvider = $configProvider;
        $this->authSession = $authSession;
        $this->backendSession = $backendSession;
    }

    /**
     * Get credit_hold attribute value from current customer.
     *
     * @return bool
     */
    private function getCustomerAttrCreditHold() : bool
    {
        $customerData = $this->backendSession->getCustomerData();

        return isset($customerData['account'][AddCustomerAttributeCreditHold::ATTRIBUTE_CODE]) && $customerData['account'][AddCustomerAttributeCreditHold::ATTRIBUTE_CODE];
    }

    /**
     * Get current customer id from admin session.
     *
     * @return int
     */
    public function getCustomerId() : int
    {
        $customerData = $this->backendSession->getCustomerData();

        return (int)$customerData['account']['id'] ?? 0;
    }

    /**
     * Set customer id to array in admin session.
     *
     * @return void
     */
    public function setCustomerIdToAdminSession(int $customerId = null) : void
    {
        if (!$customerId) {
            $customerId = $this->getCustomerId();
        }
        $customerIds = $this->getCustomerIds() ?? [];
        $customerIds[] = $customerId;
        $this->authSession->setData(self::ADMIN_SESSION_ATTRIBUTE_CUSTOMER_IDS, $customerIds);
    }

    /**
     * Get array customers id from admin session.
     *
     * @return array
     */
    private function getCustomerIds() : array
    {
        return $this->authSession->getData(self::ADMIN_SESSION_ATTRIBUTE_CUSTOMER_IDS) ?? [];
    }

    /**
     * Check to be customer id in admin session array.
     *
     * @return bool
     */
    public function checkCustomerIdInArrayAdminSession() : bool
    {
        $currentCustomerId = $this->getCustomerId();
        $customerIds = $this->getCustomerIds();
        $isCustomerIdInArray = false;
        if (!empty($customerIds) && in_array($currentCustomerId, $customerIds)) {
            $isCustomerIdInArray = true;
        }

        return $isCustomerIdInArray;
    }

    /**
     * Get options enabled.
     *
     * @return bool
     */
    public function isCreditHoldConfigEnabled() : bool
    {
        return $this->configProvider->isOptionCreditHoldEnable();
    }

    /**
     * Last check for once show.
     *
     * @return bool
     */
    public function checkForOneTimeDemoMessage() : bool
    {
        return $this->getCustomerAttrCreditHold() && $this->isCreditHoldConfigEnabled() && !$this->checkCustomerIdInArrayAdminSession();
    }

    /**
     * Get message and set customer id to admin session.
     *
     * @return string
     */
    public function getMessageAndSetCustomerIdToAdminSession() : string
    {
        $this->setCustomerIdToAdminSession();

        return $this->configProvider->getMessage();
    }
}
