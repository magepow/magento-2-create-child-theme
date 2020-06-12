<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-09 18:32:37
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-06-09 20:08:03
 */

namespace Magepow\Theme\Block\Adminhtml;

class Theme extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor.
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_theme';
        $this->_blockGroup = 'Magepow_Theme';
        $this->_headerText = __('Theme');
        $this->_addButtonLabel = __('Add New Theme');
        parent::_construct();
    }
}
