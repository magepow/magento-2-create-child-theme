<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-04 17:07:50
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-06-04 17:08:36
 */

namespace Magepow\Theme\Controller\Adminhtml\Index;

use Magento\Framework\App\Filesystem\DirectoryList;

class ExportXml extends \Magepow\Theme\Controller\Adminhtml\Action
{
    public function execute()
    {
        $fileName = 'theme.xml';

        /** @var \\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $content = $resultPage->getLayout()->createBlock('Magepow\Theme\Block\Adminhtml\Theme\Grid')->getXml();

        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
