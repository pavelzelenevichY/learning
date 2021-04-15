<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Training\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Codifi\Training\Model\AdminSessionManagement;

/**
 * Class AddCustomerIdAfterSaveToAdminSession
 * @package Codifi\Training\Observer
 */
class AddCustomerIdAfterSaveToAdminSession implements ObserverInterface
{
    /**
     * Admin session.
     *
     * @var AdminSessionManagement
     */
    private $adminSession;

    /**
     * AddCustomerIdAfterSaveToAdminSession constructor.
     *
     * @param AdminSessionManagement $adminSession
     */
    public function __construct(
        AdminSessionManagement $adminSession
    ) {
        $this->adminSession = $adminSession;
    }

    /**
     * Execute function.
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer) : void
    {
        $event = $observer->getEvent();
        $customer = $event->getCustomer();
        $customerId = (int)$customer->getId();
        $this->adminSession->setCustomerIdToAdminSession($customerId);
    }
}
