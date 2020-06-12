<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-09 19:52:46
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-06-12 11:12:39
 */

namespace Magepow\Theme\Block\Adminhtml\Theme\Edit;

use Magento\Theme\Model\Theme\Collection;
use Magento\Framework\App\Area;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Config\Model\Config\Source\Yesno
     */
    protected $_yesno;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Magento\Theme\Model\Theme
     */

    protected $_themeFactory;

    /**
     * @var \Magepow\Theme\Model\Frontend\Theme
     */
    
    protected $_themeOption;

    /**
     * @var \Magepow\Theme\Model\System\Config\Type
     */
    
    protected $_themeType;


    /**
     * @param \Magento\Backend\Block\Template\Context                    $context       
     * @param \Magento\Framework\Registry                                $registry      
     * @param \Magento\Framework\Data\FormFactory                        $formFactory   
     * @param \Magento\Config\Model\Config\Source\Yesno                  $yesno               
     * @param \Magento\Store\Model\System\Store                          $systemStore   
     * @param array                                                      $data          
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Config\Model\Config\Source\Yesno $yesno,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Theme\Model\Theme $themeFactory,
        \Magepow\Theme\Model\Frontend\Theme $themeOption,
        \Magepow\Theme\Model\System\Config\Type $themeType,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->_yesno = $yesno;

        $this->_systemStore     = $systemStore;
        $this->_themeFactory    = $themeFactory;
        $this->_themeOption     = $themeOption;
        $this->_themeType       = $themeType;
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model          = $this->getTheme();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
                [
                    'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getData('action'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                    ]
                ]
            );

        $fieldset       = $form->addFieldset('base_fieldset', ['legend' => __('Info Theme')]);
        if ($model->getId()) {
            $fieldset->addField('theme_id', 'hidden', ['name' => 'theme_id']);
        }

        $themeId        = $model->getId();
        if($model->getParentId()){
            $parentTheme = $this->_themeFactory->load($model->getParentId());
            if($parentTheme) $model->setData('parent_theme', $parentTheme->getThemePath());
        }
        $themes         = $this->_themeOption->toOptionArray();
        $types          = $this->_themeType->toOptionArray();
        $exampleParent  = $themeId ? '' : key($themes);
        $exampleName    = reset($themes) . ' Child';
        $examplePath    = key($themes)   . '_child';
        $themes         = array_merge([''=>__('Choose Parent Theme')], $themes);
        $field = $fieldset->addField('parent_theme', 'select',
            [
                'name'      => 'parent_theme',
                'label'     => __('Parent Theme'),
                'title'     => __('Parent Theme'),
                'value'     => $exampleParent,
                // 'required'  => true,
                'values'    => $themes
            ]
        );

        $field = $fieldset->addField('theme_title', 'text',
            [
                'name'      => 'theme_title',
                'label'     => __('Theme Title'),
                'title'     => __('Theme Title'),
                'required'  => true,
                'value'     => $exampleName,
                'after_element_html' => '<small>' . __('Example: %1', $exampleName) . '</small>',
            ]
        );

        $field = $fieldset->addField('theme_path', 'text',
            [
                'name'      => 'theme_path',
                'label'     => __('Theme Path'),
                'title'     => __('Theme Path'),
                'required'  => true,
                'value'     => $examplePath,
                'after_element_html' => '<small>' . __('Example: %1', $examplePath) . '</small>',
            ]
        );

        $field = $fieldset->addField('theme_type', 'select',
            [
                'name'      => 'theme_type',
                'label'     => __('Theme Type'),
                'title'     => __('Theme Type'),
                'required'  => true,
                'value'     => 0,
                'values'    => $types,
                'after_element_html' => '<small>' . __("Important: Don't change to Virtual or Staging if you don't have knowledge about this.") . '</small>',
            ]
        );

        $fieldset->addField('overwrite', 'checkbox',
            [
                'label' => __('Overwrite'),
                'title' => __('Overwrite'),
                'name' => 'overwrite',
                'value' => 1,
                // 'checked' => 'checked',
                'after_element_html' => '<small>' . __('Overwrite theme if exist') . '</small>',
            ]
        );

        // $fieldset->addField('active', 'checkbox',
        //     [
        //         'label' => __('Active'),
        //         'title' => __('Active'),
        //         'name' => 'active',
        //         // 'options' => $this->_yesno->toArray(),
        //         'value' => 1,
        //         'checked' => 'checked',
        //         'after_element_html' => '<small>' . __('Active Theme > Content > Design > Configuration') . '</small>',
        //     ]
        // );

        // $scope    = $this->getRequest()->getParam('store');
        // if($scope){
        //     $scopeId = $this->_storeManager->getStore($scope)->getId();
        //     $fieldset->addField('scope', 'hidden', array(
        //         'label'     => __('Scope'),
        //         'class'     => 'required-entry',
        //         'required'  => true,
        //         'name'      => 'scope',
        //         'value'     => 'stores',
        //     ));
        //     $fieldset->addField('scope_id', 'hidden', array(
        //         'label'     => __('Scope Id'),
        //         'class'     => 'required-entry',
        //         'required'  => true,
        //         'name'      => 'scope_id',
        //         'value'     => $scopeId,
        //     ));
        // }else {
        //     $scope   = $this->getRequest()->getParam('website');
        //     if($scope){
        //         $scopeId = $this->_storeManager->getWebsite($scope)->getId();
        //         $fieldset->addField('scope', 'hidden', array(
        //             'label'     => __('Scope'),
        //             'class'     => 'required-entry',
        //             'required'  => true,
        //             'name'      => 'scope',
        //             'value'     => 'websites',
        //         ));
        //         $fieldset->addField('scope_id', 'hidden', array(
        //             'label'     => __('Scope Id'),
        //             'class'     => 'required-entry',
        //             'required'  => true,
        //             'name'      => 'scope_id',
        //             'value'     => $scopeId,
        //         ));             
        //     }

        // }

        $form->setUseContainer(true);
        $form->addValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * @return mixed
     */
    public function getTheme()
    {
        return $this->_coreRegistry->registry('theme');
    }

}

