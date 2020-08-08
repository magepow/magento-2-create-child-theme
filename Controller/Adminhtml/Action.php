<?php

/**
 * @Author: nguyen
 * @Date:   2020-06-04 16:44:01
 * @Last Modified by:   nguyen
 * @Last Modified time: 2020-08-08 09:37:20
 */

namespace Magepow\Theme\Controller\Adminhtml;

abstract class Action extends \Magento\Backend\App\Action
{

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Core store config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $_resultForwardFactory;

    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $_resultLayoutFactory;

    /**
     * A factory that knows how to create a "page" result
     * Requires an instance of controller action in order to impose page type,
     * which is by convention is determined from the controller action class.
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var \Magento\Backend\Model\View\Result\RedirectFactory
     */
    protected $_resultRedirectFactory;

    /**
     * Registry object.
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_filesystem;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $_driver;

    /**
     * File Factory.
     *
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_fileFactory;

    /**
     * @var \Magento\Theme\Model\ThemeFactory
     */
    protected $_themeFactory;

    /**
     * @var \Magento\Theme\Model\ResourceModel\Theme\CollectionFactory
     */
    protected $_themeCollectionFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filesystem\Driver\File $driver,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Theme\Model\ThemeFactory $themeFactory,
        \Magento\Theme\Model\ResourceModel\Theme\CollectionFactory $themeCollectionFactory

    ) {
        parent::__construct($context);
        $this->_coreRegistry            = $coreRegistry;
        $this->_filesystem              = $filesystem;
        $this->_driver                  = $driver;
        $this->_fileFactory             = $fileFactory;
        $this->_resultPageFactory       = $resultPageFactory;
        $this->_resultLayoutFactory     = $resultLayoutFactory;
        $this->_resultForwardFactory    = $resultForwardFactory;
        $this->_resultRedirectFactory   = $context->getResultRedirectFactory();
        $this->_themeFactory            = $themeFactory;
        $this->_themeCollectionFactory  = $themeCollectionFactory;
    }

    protected function _isAllowed()
    {
        $namespace = (new \ReflectionObject($this))->getNamespaceName();
        $string = strtolower(str_replace(__NAMESPACE__ . '\\','', $namespace));
        $action =  explode('\\', $string);
        $action =  array_shift($action);
        return $this->_authorization->isAllowed("Magepow_Theme::theme_$action");
    }
}
