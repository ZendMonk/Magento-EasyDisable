<?php
/**
 * Rewrite of Review Helper block
 *
 * @category   ZendMonk
 * @package    ZendMonk_EasyDisable
 * @author     Carl Monk <@ZendMonk>
 */
class ZendMonk_EasyDisable_Block_Review_Helper extends Mage_Review_Block_Helper
{
	/**
     * Rewrite core function to forbid display of Review Summary HTML if Reviews are disabled
     *
     * @param Mage_Catalog_Model_Product $product
	 * @param string $templateType
     * @param bool $displayIfNoReviews
	 * @return mixed
     */
    public function getSummaryHtml($product, $templateType, $displayIfNoReviews)
    {
        if (empty($this->_availableTemplates[$templateType])) {
            $templateType = 'default';
        }
        $this->setTemplate($this->_availableTemplates[$templateType]);
		
		$helper = Mage::helper('zendmonk_easydisable');
		$displayIfEmpty = $helper->isReviewsDisabled() ? false : $displayIfNoReviews;
		$this->setDisplayIfEmpty($displayIfEmpty);
		
        if (!$product->getRatingSummary()) {
            Mage::getModel('review/review')
               ->getEntitySummary($product, Mage::app()->getStore()->getId());
        }
        $this->setProduct($product);

        return $this->toHtml();
    }

	/**
     * Rewrite core function to return false if Reviews are disabled
     *
     * @return mixed
     */
    public function getReviewsCount()
    {
		$helper = Mage::helper('zendmonk_easydisable');
		if ($helper->isReviewsDisabled()) { 
			return false;
		}
		return parent::getReviewsCount();
    }
}
