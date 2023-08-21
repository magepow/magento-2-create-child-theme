<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-04 17:06:09
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-06-04 17:06:28
 */

namespace Magepow\Theme\Controller\Adminhtml\Index;

class MassDelete extends \Magepow\Theme\Controller\Adminhtml\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $themeIds = $this->getRequest()->getParam('theme');
        if (!is_array($themeIds) || empty($themeIds)) {
            $this->messageManager->addError(__('Please select theme(s).'));
        } else {
            $collection = $this->_themeCollectionFactory->create()
                ->addFieldToFilter('theme_id', ['in' => $themeIds]);
            try {
                foreach ($collection as $item) {
                    $item->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($themeIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/');
    }
}
