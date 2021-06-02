<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Training\Model;

use Magento\Customer\Model\Session;

/**
 * Class CustomerSessionManagement
 * @package Codifi\Training\Model
 */
class CustomerSessionManagement
{
    /**
     * Customer session.
     *
     * @var Session
     */
    private $session;

    /**
     * CustomerSessionManagement constructor.
     *
     * @param Session $session
     */
    public function __construct(
        Session $session
    ) {
        $this->session = $session;
    }

    /**
     * Set flag value true.
     */
    public function setFlag(): void
    {
        $this->session->setData(ConfigProvider::SESSION_FLAG, true);
    }
}
