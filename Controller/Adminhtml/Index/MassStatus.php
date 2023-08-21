<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-04 17:05:51
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-06-04 17:05:58
 */

namespace Magepow\Theme\Controller\Adminhtml\Index;

class MassStatus extends \Magepow\Theme\Controller\Adminhtml\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $themeIds = $this->getRequest()->getParam('theme');
        $status = $this->getRequest()->getParam('status');
        $storeViewId = $this->getRequest()->getParam('store');
        if (!is_array($themeIds) || empty($themeIds)) {
            $this->messageManager->addError(__('Please select Theme(s).'));
        } else {
            $collection = $this->_themeCollectionFactory->create()
                // ->setStoreViewId($storeViewId)
                ->addFieldToFilter('theme_id', ['in' => $themeIds]);
            try {
                foreach ($collection as $item) {
                    $item->setStoreViewId($storeViewId)
                        ->setStatus($status)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been changed status.', count($themeIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('*/*/', ['store' => $this->getRequest()->getParam('store')]);
    }
}
