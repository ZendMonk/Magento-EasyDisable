<?php
/**
 * Remove Breadcrumbs From config source
 *
 * @category   ZendMonk
 * @package    ZendMonk_EasyDisable
 * @author     Carl Monk <@ZendMonk>
 */
class ZendMonk_EasyDisable_Model_System_Config_Source_Removebreadcrumbsfrom
{
	/**
     * Retrieve options
     *
     * @return array
     */
	public function toOptionArray()
    {
		return array(
            array('value' => 'catalog/category', 'label' => Mage::helper('zendmonk_easydisable')->__('Category Pages')),
            array('value' => 'catalog/product', 'label' => Mage::helper('zendmonk_easydisable')->__('Product Pages')),
			array('value' => 'cms/page', 'label' => Mage::helper('zendmonk_easydisable')->__('CMS Pages')),
            array('value' => 'catalogsearch/result', 'label' => Mage::helper('zendmonk_easydisable')->__('Search Results (Standard Search)')),
			array('value' => 'catalogsearch/advanced', 'label' => Mage::helper('zendmonk_easydisable')->__('Advanced Search (Search Form and Results)')),
			array('value' => 'sales/guest', 'label' => Mage::helper('zendmonk_easydisable')->__('Orders and Returns')),
			array('value' => 'review/product', 'label' => Mage::helper('zendmonk_easydisable')->__('Product Reviews'))
        );
    }
}