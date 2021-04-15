<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Training\UI\Component\Listing\Notes\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

/**
 * Class Actions
 * @package Codifi\Training\UI\Component\Listing\Notes\Column
 */
class Actions extends Column
{
    /**
     * Url interface
     *
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * Actions constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                if (isset($item['note_id'])) {
                    $item[$name]['edit'] = [
                        'callback' => [
                            [
                                'provider' => 'customer_form.areas.customer_notes.customer_notes'
                                    . '.customer_note_form_modal.customer_note_form_loader',
                                'target' => 'destroyInserted',
                            ],
                            [
                                'provider' => 'customer_form.areas.customer_notes.customer_notes'
                                    . '.customer_note_form_modal',
                                'target' => 'openModal',
                            ],
                            [
                                'provider' => 'customer_form.areas.customer_notes.customer_notes'
                                    . '.customer_note_form_modal.customer_note_form_loader',
                                'target' => 'render',
                                'params' => [
                                    'note_id' => $item['note_id'],
                                ],
                            ]
                        ],
                        'href' => '#',
                        'label' => __('Edit'),
                        'hidden' => false,
                    ];
                }
            }
        }

        return $dataSource;
    }
}
