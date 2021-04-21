<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Plugin\AdminOrder;

use Codifi\Sales\Helper\Config;
use Magento\Sales\Model\AdminOrder\Create;
use \Closure;

/**
 * Class CreatePlugin
 * @package Codifi\Sales\Plugin\AdminOrder
 */
class CreatePlugin
{
    /**
     * @param Create $subject
     * @param Closure $proceed
     */
    public function aroundAddField(Create $subject, Closure $proceed) {
        if (isset($data['order_type'])) {
            $subject->setData(Config::ORDER_TYPE_CODE, $data['order_type']);
        }
        return $proceed;
    }
}
