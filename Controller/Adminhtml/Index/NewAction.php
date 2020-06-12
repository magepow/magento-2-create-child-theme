<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-04 17:05:05
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-06-04 17:05:30
 */

namespace Magepow\Theme\Controller\Adminhtml\Index;

class NewAction extends \Magepow\Theme\Controller\Adminhtml\Action
{
    public function execute()
    {
        $resultForward = $this->_resultForwardFactory->create();

        return $resultForward->forward('edit');
    }
}
