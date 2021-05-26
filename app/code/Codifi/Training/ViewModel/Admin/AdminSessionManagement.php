<?php


namespace Codifi\Training\ViewModel\Admin;

use Codifi\Training\Model\ConfigProvider;
use Codifi\Training\Setup\Patch\Data\AddCustomerAttributeCreditHold;
use Magento\Backend\Model\Auth\Session;
use Magento\Backend\Model\Session as BackendSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Codifi\Training\Model\AdminSessionManagement as AdminSessionModel;

class AdminSessionManagement implements ArgumentInterface
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
     * Admin session management model
     *
     * @var AdminSessionModel
     */
    private $adminSessionModel;

    /**
     * AdminSessionManagement constructor.
     *
     * @param ConfigProvider $configProvider
     * @param Session $authSession
     * @param BackendSession $backendSession
     * @param AdminSessionModel $adminSessionModel
     */
    public function __construct(
        ConfigProvider $configProvider,
        Session $authSession,
        BackendSession $backendSession,
        AdminSessionModel $adminSessionModel
    ) {
        $this->configProvider = $configProvider;
        $this->authSession = $authSession;
        $this->backendSession = $backendSession;
        $this->adminSessionModel = $adminSessionModel;
    }

    /**
     * Get credit_hold attribute value from current customer.
     *
     * @return bool
     */
    private function getCustomerAttrCreditHold(): bool
    {
        $customerData = $this->backendSession->getCustomerData();

        return isset($customerData['account'][AddCustomerAttributeCreditHold::ATTRIBUTE_CODE]) &&
            $customerData['account'][AddCustomerAttributeCreditHold::ATTRIBUTE_CODE];
    }


    /**
     * Check to be customer id in admin session array.
     *
     * @return bool
     */
    public function checkCustomerIdInArrayAdminSession(): bool
    {
        $currentCustomerId = $this->adminSessionModel->getCustomerId();
        $customerIds = $this->adminSessionModel->getCustomerIds();
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
    public function isCreditHoldConfigEnabled(): bool
    {
        return $this->configProvider->isOptionCreditHoldEnable();
    }

    /**
     * Last check for once show.
     *
     * @return bool
     */
    public function checkForOneTimeDemoMessage(): bool
    {
        return $this->getCustomerAttrCreditHold() &&
            $this->isCreditHoldConfigEnabled() && !$this->checkCustomerIdInArrayAdminSession();
    }

    /**
     * Get message and set customer id to admin session.
     *
     * @return string
     */
    public function getMessageAndSetCustomerIdToAdminSession(): string
    {
        $this->adminSessionModel->setCustomerIdToAdminSession();

        return $this->configProvider->getMessage();
    }
}
