<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-09 15:34:33
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-06-12 13:08:30
 */

namespace Magepow\Theme\Block\Adminhtml\Theme;

use Magento\Framework\App\Area;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{

    /**
     * Review data
     *
     * @var \Magento\Review\Helper\Data
     */
    protected $_status;

    /**
     * theme collection factory.
     *
     * @var \Magento\Theme\Model\ThemeFactory
     */
    protected $_themeFactory;

    /**
     * construct.
     *
     * @param \Magento\Backend\Block\Template\Context                         $context
     * @param \Magento\Backend\Helper\Data                                    $backendHelper
     * @param \Magento\Theme\Model\Theme                                      $themeFactory
     * @param array                                                           $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Theme\Model\Theme $themeFactory,
        \Magepow\Theme\Model\System\Config\Status $status,
    
        array $data = []
    ) {

        $this->_status       = $status;
        $this->_themeFactory = $themeFactory;

        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('themeGrid');
        $this->setDefaultSort('theme_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $store = $this->getRequest()->getParam('store');
        $collection = $this->_themeFactory->getCollection();
        $collection->addAreaFilter(\Magento\Framework\App\Area::AREA_FRONTEND);
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'theme_id',
            [
                'header' => __('Theme ID'),
                'type' => 'number',
                'index' => 'theme_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );

        $this->addColumn(
            'parent_id',
            [
                'header' => __('Parent Id'),
                'type' => 'text',
                'index' => 'parent_id',
                'header_css_class' => 'col-title',
                'column_css_class' => 'col-title',
            ]
        );

        $this->addColumn(
            'theme_path',
            [
                'header' => __('Theme Path'),
                'type' => 'text',
                'index' => 'theme_path',
                'header_css_class' => 'col-title',
                'column_css_class' => 'col-title',
            ]
        );

        $this->addColumn(
            'theme_title',
            [
                'header' => __('Theme Title'),
                'type' => 'text',
                'index' => 'theme_title',
                'header_css_class' => 'col-title',
                'column_css_class' => 'col-title',
            ]
        );

        $this->addColumn(
            'type',
            [
                'header' => __('Type'),
                'type' => 'text',
                'index' => 'type',
                'header_css_class' => 'col-title',
                'column_css_class' => 'col-title',
            ]
        );

        // $this->addColumn(
        //     'code',
        //     [
        //         'header' => __('Code'),
        //         'type' => 'text',
        //         'index' => 'code',
        //         'header_css_class' => 'col-title',
        //         'column_css_class' => 'col-title',
        //     ]
        // );

        // if (!$this->_storeManager->isSingleStoreMode()) {
        //     $this->addColumn(
        //         'stores',
        //         [
        //             'header' => __('Store View'),
        //             'index' => 'stores',
        //             'type' => 'store',
        //             'store_all' => true,
        //             'store_view' => true,
        //             'sortable' => false,
        //             'filter_condition_callback' => [$this, '_filterStoreCondition']
        //         ]
        //     );
        // }

        // $this->addColumn(
        //     'status',
        //     [
        //         'header' => __('Status'),
        //         'index' => 'status',
        //         'type' => 'options',
        //         'options' => $this->_status->getOptionArray(),
        //     ]
        // );

        $this->addColumn(
            'edit',
            [
                'header' => __('Edit'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => ['base' => '*/*/edit'],
                        'field' => 'theme_id',
                    ],
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action',
            ]
        );
        $this->addExportType('*/*/exportCsv', __('CSV'));
        $this->addExportType('*/*/exportXml', __('XML'));
        $this->addExportType('*/*/exportExcel', __('Excel'));

        return parent::_prepareColumns();
    }

    /**
     * get theme vailable option
     *
     * @return array
     */

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('theme_id');
        $this->getMassactionBlock()->setFormFieldName('theme');

        // $this->getMassactionBlock()->addItem(
        //     'delete',
        //     [
        //         'label' => __('Delete'),
        //         'url' => $this->getUrl('magepowtheme/*/massDelete'),
        //         'confirm' => __('Are you sure?'),
        //     ]
        // );

        // $statuses = $this->_status->getOptionArray();

        // array_unshift($statuses, ['label' => '', 'value' => '']);
        // $this->getMassactionBlock()->addItem(
        //     'status',
        //     [
        //         'label' => __('Change status'),
        //         'url' => $this->getUrl('magepowtheme/*/massStatus', ['_current' => true]),
        //         'additional' => [
        //             'visibility' => [
        //                 'name' => 'status',
        //                 'type' => 'select',
        //                 'class' => 'required-entry',
        //                 'label' => __('Status'),
        //                 'values' => $statuses,
        //             ],
        //         ],
        //     ]
        // );

        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current' => true]);
    }

    /**
     * get row url
     * @param  object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/*/edit',
            ['theme_id' => $row->getId()]
        );
    }
}
