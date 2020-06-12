<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-04 17:09:25
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-06-04 17:09:55
 */

namespace Magepow\Theme\Controller\Adminhtml\Index;

use Magento\Framework\App\Filesystem\DirectoryList;

class ExportCsv extends \Magepow\Theme\Controller\Adminhtml\Action
{
    public function execute()
    {
        $fileName = 'theme.csv';

        /** @var \\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $content = $resultPage->getLayout()->createBlock('Magepow\Theme\Block\Adminhtml\Theme\Grid')->getCsv();

        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
