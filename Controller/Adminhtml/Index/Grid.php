<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-04 17:07:21
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-06-04 17:07:42
 */

namespace Magepow\Theme\Controller\Adminhtml\Index;

class Grid extends \Magepow\Theme\Controller\Adminhtml\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $resultLayout = $this->_resultLayoutFactory->create();

        return $resultLayout;
    }
}
