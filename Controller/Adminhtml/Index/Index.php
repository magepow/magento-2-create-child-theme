<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-04 17:06:48
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-06-09 18:52:49
 */

namespace Magepow\Theme\Controller\Adminhtml\Index;

class Index extends \Magepow\Theme\Controller\Adminhtml\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        if ($this->getRequest()->getQuery('ajax')) {
            $resultForward = $this->_resultForwardFactory->create();
            $resultForward->forward('grid');

            return $resultForward;
        }

        $resultPage = $this->_resultPageFactory->create();

        return $resultPage;
    }
}
