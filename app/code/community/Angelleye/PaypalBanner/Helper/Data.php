<?php
class Angelleye_PaypalBanner_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Configuration-controller-action map
     * @var array
     */
    public static $_codeMap = array(
        'catalog.product.view'=>'catalog_product',
        'catalog.category.view'=>'catalog_category',
        'cms.index.index'=>'homepage',
        'checkout.cart.index'=>'checkout_cart'
    );

    /**
     * Get configuration settings for current page
     * @return Varien_Object $config
     */
    public function getSectionConfig()
    {
        $config = new Varien_Object();
        $module = Mage::app()->getRequest()->getModuleName();
        $controller = Mage::app()->getRequest()->getControllerName();
        $action = Mage::app()->getRequest()->getActionName();

        if (isset(self::$_codeMap[$module.'.'.$controller.'.'.$action])){
            $pageCode = self::$_codeMap[$module.'.'.$controller.'.'.$action];
            $size = Mage::getStoreConfig('paypalbanner/'.$pageCode.'/size');
            $position = Mage::getStoreConfig('paypalbanner/'.$pageCode.'/position');
            list($positionHorizontal, $positionVertical) = explode('-',$position);
            $display = Mage::getStoreConfig('paypalbanner/'.$pageCode.'/display');
            $config->setPageCode($pageCode)
                ->setDisplay($display)
                ->setSize($size)
                ->setPositionHorizontal($positionHorizontal)
                ->setPositionVertical($positionVertical);
        }
        return $config;
    }

    /**
     * Get html code for banner snippet
     *
     * @param string $pageCode
     * @return string $snippet
     */
    public function getSnippetCode($pageCode='')
    {
        if (!Mage::getStoreConfig('paypalbanner/settings/active') || !$this->getSectionConfig()->getDisplay()) {
            return '';
        }

        $id = Mage::getStoreConfig('paypalbanner/settings/id');
        $container = Mage::getStoreConfig('paypalbanner/settings/container');
        $size = $this->getSectionConfig()->getSize();

        $snippet  = '<script type="text/javascript" data-pp-pubid="'.$id.'" data-pp-placementtype="'.$size.'">
    (function (d, t) {
        "use strict";
        var s = d.getElementsByTagName(t)[0], n = d.createElement(t);
        n.src = "//paypal.adtag.where.com/merchant.js";
        s.parentNode.insertBefore(n, s);
    }(document, "script"));
</script>';
        if (!empty($container)) {
            $snippet = str_replace('{container}', $snippet, $container);
        }
        return $snippet;
    }
}