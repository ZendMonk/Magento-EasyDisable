<?php
/**
 * Rewrite of Catalog Product Compate helper
 *
 * @category   ZendMonk
 * @package    ZendMonk_EasyDisable
 * @author     Carl Monk <@ZendMonk>
 */
class ZendMonk_EasyDisable_Helper_Catalog_Product_Compare extends Mage_Catalog_Helper_Product_Compare
{
	/**
     *  Rewrite core function to return false if Product Comparison is disabled
     *
	 *	@param Mage_Catalog_Model_Product $product
     *  @return mixed
     */
	public function getAddUrl($product)
    {
		if (Mage::helper('zendmonk_easydisable')->isComparisonDisabled()) {
			return false;
		}
		return parent::getAddUrl($product);
    }
}
