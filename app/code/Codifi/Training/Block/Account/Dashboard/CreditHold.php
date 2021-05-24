<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Training\Block\Account\Dashboard;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Codifi\Training\Model\CustomerSessionManagement;

/**
 * Class CreditHold
 * @package Codifi\Training\Block\Account\Dashboard
 */
class CreditHold extends Template
{
    /**
     * Customer session.
     *
     * @var CustomerSessionManagement
     */
    private $customerSession;

    /**
     * CreditHold constructor.
     *
     * @param Context $context
     * @param CustomerSessionManagement $customerSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        CustomerSessionManagement $customerSession,
        array $data = []
    ) {
        $this->customerSession = $customerSession;

        parent::__construct($context, $data);
    }

    /**
     * Check for one time demo message.
     *
     * @return bool
     */
    public function checkForOneTimeDemoMessage(): bool
    {
        return $this->customerSession->checkForOneTimeDemoMessage();
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->customerSession->getMessageAndCallSetFlag();
    }

    /**
     * Get current customer id.
     *
     * @return int
     */
    public function getCustomerId(): int
    {
        return (int)$this->customerSession->getCustomerId();
    }

    /**
     * Get url save controller
     *
     * @return string
     */
    public function getSaveUrl(): string
    {
        return $this->_urlBuilder->getUrl('customer/note/save');
    }
}
