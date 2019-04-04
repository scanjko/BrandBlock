<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Product description block
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */

namespace Pixel\BrandBlock\Block\Product\View;

use Magento\Catalog\Model\Product;

/**
 * @api
 * @since 100.0.2
 */
class Brand extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Product
     */
    protected $_product = null;
    protected $_brand = null;
    protected $_catID = null;
    protected $_categoryFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        $this->_categoryFactory = $categoryFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = $this->_coreRegistry->registry('product');
        }
        return $this->_product;
    }

    private function getBrandName()
    {
        if ($this->_product->getAttributeText('brand_info')) {
            $this->_brand = strtolower($this->_product->getAttributeText('brand_info'));
        }

        return $this->_brand;
    }

    public function getCategoryIDByName()
    {
        $categoryName = $this->getBrandName();
        if ($categoryName) {
            $collection = $this->_categoryFactory
                ->create()
                ->getCollection()
                ->addAttributeToFilter('name', $categoryName)
                ->setPageSize(1);

            if ($collection->getSize()) {
                $this->_catID = $collection->getFirstItem()->getId();
            }
        }


        return $this->_catID;

    }

    public function getCategoryCollection()
    {
        $this->getCategoryIDByName();
        $cat = null;
        if ($this->_catID > 0) {
            $cat = $this->_categoryFactory->create()->load($this->_catID);
        }

        return $cat;
    }


}
