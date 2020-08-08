<?php

/**
 * @Author: nguyen
 * @Date:   2020-05-31 14:56:43
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-08-08 10:06:20
 */

namespace Magepow\Theme\Controller\Adminhtml\Index;

use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magepow\Theme\Controller\Adminhtml\Action
{

    protected $defaultTheme = [
        'Magento/blank',
        'Magento/luma',
        'Magento/backend',
        'Alothemes/framework'
    ];
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $resultRedirect = $this->_resultRedirectFactory->create();

        if ($data = $this->getRequest()->getPostValue()) {
            $id = $this->getRequest()->getParam('theme_id');
            $storeViewId = $this->getRequest()->getParam('store');
            $model = $this->_objectManager->create('Magento\Theme\Model\Theme');
            if ($id){
                $model->load($id);
            }

            $parentTheme    = $data['parent_theme'];
            $themeTitle     = $data['theme_title'];
            $themePath      = isset($data['theme_path']) ? $data['theme_path'] : $model->getThemePath();
            $themePath      = trim($themePath);
            $themePath      = str_replace(' ', '_', $themePath);;
            $isRePath       = false;
            if($model->getId()){
                $isRePath = ($themePath != $model->getThemePath());
            }
            if($id && in_array($themePath, $this->defaultTheme)){
                $this->messageManager->addError(__('You can\'t edit default theme %1.', $themePath));
                $this->_getSession()->setFormData($data);
                return $resultRedirect->setPath('*/*/edit');                
            }
            try{
                $frontend = 'design' . DIRECTORY_SEPARATOR . 'frontend';
                $dir = $this->_filesystem->getDirectoryWrite(DirectoryList::APP);
                $theme = $this->_objectManager->create('Magento\Theme\Model\Theme');
                if(!preg_match('/^[a-zA-Z0-9_]+\/+[a-zA-Z0-9_]+$/', $themePath)){
                    $this->messageManager->addError(__('Theme Path %1 wrong format', $themePath));
                    $this->_getSession()->setFormData($data);
                    return $resultRedirect->setPath('*/*/edit');
                }
                $theme = $theme->load($themePath, 'theme_path');
                if(!isset($data['overwrite'])){
                    if(!$id && $theme->getThemeId()){
                        $this->messageManager->addError(__('Theme %1 already exist in database', $themePath));
                        $this->_getSession()->setFormData($data);
                        return $resultRedirect->setPath('*/*/edit');
                    }
                }
                $themes = $this->_objectManager->create('Magento\Theme\Model\Theme')->getCollection()->addFieldToSelect('*')->addFieldToFilter('theme_path', $parentTheme);
                $parent = $themes->getFirstItem();
                if(!$parent){
                    $this->messageManager->addError(__('Parent theme %1 not exist', $parentTheme));
                    $this->_getSession()->setFormData($data);
                    return $resultRedirect->setPath('*/*/edit');
                }
                if($parentTheme == $themePath || $parent->getId() == $model->getId()){
                    $this->messageManager->addError(__('Error Parent theme and Child theme same value.'));
                    $this->_getSession()->setFormData($data);
                    return $resultRedirect->setPath('*/*/edit');                
                }

                $filePathXml    = $frontend . DIRECTORY_SEPARATOR . $themePath . DIRECTORY_SEPARATOR . 'theme.xml';
                if(file_exists($filePathXml)){

                }

                if($isRePath){
                    $oldPath = $frontend . DIRECTORY_SEPARATOR . $model->getThemePath();
                    $oldPath = $dir->getAbsolutePath($oldPath);
                    $newPath = $frontend . DIRECTORY_SEPARATOR . $themePath;
                    $newPath = $dir->getAbsolutePath($newPath);
                    $this->_driver->rename($oldPath, $newPath);
                }
                $dir->writeFile($filePathXml, 'tmp');
                $data['theme_path']    = $themePath;
                $data['parent_id']     = $parent->getData('theme_id');
                $data['theme_title']   = $themeTitle;
                $data['preview_image'] = NULL;
                $data['area']          = 'frontend';
                $data['is_featured']   = 0;
                $data['type']          = $data['theme_type'];
                $data['code']          = $themePath;
                if(isset($data['overwrite']) && $model->getThemeId()){
                    $model->addData($data);
                }else {
                    $model->setData($data);                   
                }

                $model->save();

                $themeXml  = '<!--
/**
 * Copyright © Alothemes, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
-->
<theme xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Config/etc/theme.xsd">
    <title>'  . $themeTitle  . '</title>
    <parent>' . $parentTheme . '</parent>
</theme>';

                $backupFilePath = $dir->getAbsolutePath($filePathXml);
                $dir->writeFile($filePathXml, $themeXml);

                $filePathPhp    = $frontend . DIRECTORY_SEPARATOR . $themePath . DIRECTORY_SEPARATOR . 'registration.php';

                $registrationPhp   = "<?php
/**
 * Copyright © 2020 Alothemes. All rights reserved.
 * See COPYING.txt for license details.
 */

\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::THEME,
    'frontend/" . $themePath . "',
    __DIR__
);";

                $dir->writeFile($filePathPhp, $registrationPhp);

                $this->messageManager->addSuccess(__('The theme "%1" has been saved', $themePath));

                if ($this->getRequest()->getParam('back') === 'edit') {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        [
                            'theme_id' => $model->getId(),
                            '_current' => true,
                            'store' => $storeViewId,
                            'current_theme_id' => $this->getRequest()->getParam('current_theme_id'),
                            'saveandclose' => $this->getRequest()->getParam('saveandclose'),
                        ]
                    );
                } elseif ($this->getRequest()->getParam('back') === 'new') {
                    return $resultRedirect->setPath(
                        '*/*/new',
                        ['_current' => TRUE]
                    );
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                    $this->_getSession()->setFormData($data);
                    $this->messageManager->addError(__('Can\'t create child theme error "%1"', $e->getMessage()));
            }
        }

        return $resultRedirect->setPath('*/*/edit');

    }

}
