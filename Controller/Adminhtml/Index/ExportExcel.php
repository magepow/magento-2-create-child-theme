<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-04 17:08:47
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-06-04 17:09:18
 */

namespace Magepow\Theme\Controller\Adminhtml\Index;

use Magento\Framework\App\Filesystem\DirectoryList;

class ExportExcel extends \Magepow\Theme\Controller\Adminhtml\Action
{
    public function execute()
    {
        $fileName = 'theme.xls';

        /** @var \\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $content = $resultPage->getLayout()->createBlock('Magepow\Theme\Block\Adminhtml\Theme\Grid')->getExcel();

        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
