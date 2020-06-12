<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-09 19:40:03
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-06-09 19:41:14
 */

namespace Magepow\Theme\Block\Adminhtml\Theme;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * _construct
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'theme_id';
        $this->_blockGroup = 'Magepow_Theme';
        $this->_controller = 'adminhtml_theme';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Theme'));
        $this->buttonList->update('delete', 'label', __('Delete'));

        if ($this->getRequest()->getParam('current_theme_id')) {
            $this->buttonList->remove('save');
            $this->buttonList->remove('delete');

            $this->buttonList->remove('back');
            $this->buttonList->add(
                'close_window',
                [
                    'label' => __('Close Window'),
                    'onclick' => 'window.close();',
                ],
                10
            );

            $this->buttonList->add(
                'save_and_continue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'onclick' => 'customsaveAndContinueEdit()',
                ],
                10
            );

            $this->buttonList->add(
                'save_and_close',
                [
                    'label' => __('Save and Close'),
                    'class' => 'save_and_close',
                    'onclick' => 'saveAndCloseWindow()',
                ],
                10
            );

            $this->_formScripts[] = "
                require(['jquery'], function($){
                    $(document).ready(function(){
                        var input = $('<input class=\"custom-button-submit\" type=\"submit\" hidden=\"true\" />');
                        $(edit_form).append(input);

                        window.customsaveAndContinueEdit = function (){
                            edit_form.action = '".$this->getSaveAndContinueUrl()."';
                            $('.custom-button-submit').trigger('click');

                        }

                        window.saveAndCloseWindow = function (){
                            edit_form.action = '".$this->getSaveAndCloseWindowUrl()."';
                            $('.custom-button-submit').trigger('click');
                        }
                    });
                });
            ";

            if ($themeId = $this->getRequest()->getParam('theme_id')) {
                $this->_formScripts[] = '
                    window.theme_id = '.$themeId.';
                ';
            }
        } else {
            $this->buttonList->add(
                'save_and_continue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ],
                ],
                10
            );
        }

        if ($this->getRequest()->getParam('saveandclose')) {
            $this->_formScripts[] = 'window.close();';
        }
    }

    /**
     * Retrieve the save and continue edit Url.
     *
     * @return string
     */
    protected function getSaveAndContinueUrl()
    {
        return $this->getUrl(
            '*/*/save',
            [
                '_current' => true,
                'back' => 'edit',
                'tab' => '{{tab_id}}',
                'store' => $this->getRequest()->getParam('store'),
                'theme_id' => $this->getRequest()->getParam('theme_id'),
                'current_theme_id' => $this->getRequest()->getParam('current_theme_id'),
            ]
        );
    }

    /**
     * Retrieve the save and continue edit Url.
     *
     * @return string
     */
    protected function getSaveAndCloseWindowUrl()
    {
        return $this->getUrl(
            '*/*/save',
            [
                '_current' => true,
                'back' => 'edit',
                'tab' => '{{tab_id}}',
                'store' => $this->getRequest()->getParam('store'),
                'theme_id' => $this->getRequest()->getParam('theme_id'),
                'current_theme_id' => $this->getRequest()->getParam('current_theme_id'),
                'saveandclose' => 1,
            ]
        );
    }
}
