<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-04 17:10:01
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-06-12 10:49:22
 */

namespace Magepow\Theme\Controller\Adminhtml\Index;

class Edit extends \Magepow\Theme\Controller\Adminhtml\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('theme_id');
        $storeViewId = $this->getRequest()->getParam('store');
        $model = $this->_themeFactory->create();

        if ($id) {
            $model->setStoreViewId($storeViewId)->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This Theme no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            } else {
                $model->setData('theme_type', $model->getData('type'));
            }
        }

        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('theme', $model);

        $resultPage = $this->_resultPageFactory->create();

        return $resultPage;
    }
}
