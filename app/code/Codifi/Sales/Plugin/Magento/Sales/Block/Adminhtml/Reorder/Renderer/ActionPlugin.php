<?php
/**
 * Codifi_Sales
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Sales\Plugin\Magento\Sales\Block\Adminhtml\Reorder\Renderer;

use Magento\Sales\Model\OrderRepository;
use Magento\Sales\Block\Adminhtml\Reorder\Renderer\Action;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class ActionPlugin
 * @package Codifi\Sales\Plugin\Magento\Sales\Block\Adminhtml\Reorder\Renderer
 */
class ActionPlugin
{
    /**
     * Order repository
     *
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * ActionPlugin constructor.
     *
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Hide reorder link if order_type - credit hold
     *
     * @param Action $subject
     * @param callable $proceed
     * @param array $actionArray
     * @return null
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function aroundAddToActions(Action $subject, callable $proceed, array $actionArray)
    {
        $url = $actionArray["@"]['href'];
        $orderId = $this->getOrderIdParamFromUrl($url);

        if ($orderId) {
            $currentOrder = $this->orderRepository->get($orderId);
            $orderType = $currentOrder->getOrderType();

            if ($orderType !== 'CREDIT_HOLD') {
                $proceed($actionArray);
            }
        }

        return ;
    }

    /**
     * Get order id function
     *
     * @param $url
     * @return string
     */
    private function getOrderIdParamFromUrl($url): string
    {
        $urlPath = parse_url($url, PHP_URL_PATH);
        $urlInArray = explode('/', $urlPath);

        $orderId = '';
        $flag = false;
        foreach ($urlInArray as $item) {
            if ($flag === true) {
                $orderId = $item;
                break;
            }
            if ($item === "order_id") {
                $flag = true;
            }
        }

        return $orderId;
    }
}
