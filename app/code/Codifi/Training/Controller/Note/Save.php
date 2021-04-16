<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Training\Controller\Note;

use Codifi\Training\Model\Note\NoteSave;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultInterface;
use Exception;

/**
 * Class Save
 * @package Codifi\Training\Controller\Note
 */
class Save extends Action
{
    /**
     * Note save model
     *
     * @var NoteSave
     */
    private $noteSave;

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param NoteSave $noteSave
     */
    public function __construct(
        Context $context,
        NoteSave $noteSave
    ) {
        parent::__construct($context);
        $this->noteSave = $noteSave;
    }

    /**
     * Execute function
     *
     * @return ResponseInterface|Json|ResultInterface
     * @throws Exception
     */
    public function execute()
    {
        $note = $this->getRequest()->getParam('note');
        $customerId = $this->getRequest()->getParam('customer_id');

        $data = [
            'note' => $note,
            'customer_id' => $customerId
        ];

        return $this->noteSave->save($data);
    }
}
