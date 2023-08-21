<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-04 17:10:47
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-06-12 14:57:42
 */

namespace Magepow\Theme\Controller\Adminhtml\Index;

use Magento\Framework\App\Filesystem\DirectoryList;

class Delete extends \Magepow\Theme\Controller\Adminhtml\Action
{
    protected $_dir;
    public function execute()
    {
        $id = $this->getRequest()->getParam('theme_id');
        try {
            /* Delete theme file */
            $theme = $this->_themeFactory->create()->load($id);
            if($theme->getId()){
                $this->_dir     = $this->_filesystem->getDirectoryWrite(DirectoryList::APP);
                $filesystemIo   = $this->_objectManager->create('Magento\Framework\Filesystem\Io\File');
                $frontend       = 'design' . DIRECTORY_SEPARATOR . 'frontend';
                $themePath      = $theme->getThemePath();
                $themeDir       = $frontend . DIRECTORY_SEPARATOR . $themePath;
                $themePathDir   = $this->_dir->getAbsolutePath($themeDir);
                $filePathXml    = $this->_dir->getAbsolutePath($themeDir . DIRECTORY_SEPARATOR . 'theme.xml');
                $filesystemIo->mv($filePathXml, $filePathXml . '_');
                $filePathPhp    = $this->_dir->getAbsolutePath( $themeDir . DIRECTORY_SEPARATOR . 'registration.php');
                $filesystemIo->mv($filePathPhp, $filePathPhp . '_');
                $this->messageManager->addWarning(__("The Theme temporary disable you can go to path %1 delete it.", $themePathDir));
                // $this->messageManager->addNotice(__("No import  %1", $backupFilePath));
            }
            $item = $this->_themeFactory->create()->setId($id);
            $item->delete();
            $this->messageManager->addSuccess(
                __('Theme record delete successfully !')
            );
            $collection      = $this->_objectManager->create('Magento\Config\Model\ResourceModel\Config\Data\Collection');
            $config     = $collection->addFieldToSelect('*')->addFieldToFilter('path', 'design/theme/theme_id')->addFieldToFilter('value',$id);
            $ids   = [];
            foreach ($config as $cfg) {
                $cfg->setValue(2); /* Luma theme */
                $cfg->save();
            }
            // $config->save();

        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
