<?php
/**
 * Codifi_CustomerRequest
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\CustomerRequest\Block\Request;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Form
 * @package Codifi\CustomerRequest\Block\Request
 */
class Form extends Template
{
    /**
     * Customer session.
     *
     * @var Session
     */
    private $customerSession;

    /**
     * CreditHold constructor.
     *
     * @param Context $context
     * @param Session $customerSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * Get current customer id
     *
     * @return int
     */
    public function getCustomerId(): int
    {
        return (int)$this->customerSession->getCustomerId();
    }

    /**
     * Get current customer name
     *
     * @return string
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCustomerName(): string
    {
        $fullName = '';
        $customerData = $this->customerSession->getCustomerData();
        $firstname = $customerData->getFirstname();
        $lastname = $customerData->getLastname();

        if ($firstname && $lastname) {
            $fullName = $firstname . ' ' . $lastname;
        }

        return $fullName;
    }

    /**
     * Get current customer email
     *
     * @return string
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCustomerEmail(): string
    {
        $customerData = $this->customerSession->getCustomerData();

        return $customerData->getEmail();
    }

    /**
     * Get url save controller
     *
     * @return string
     */
    public function getSubmitUrl(): string
    {
        return $this->_urlBuilder->getUrl('customer/request/save');
    }
}
