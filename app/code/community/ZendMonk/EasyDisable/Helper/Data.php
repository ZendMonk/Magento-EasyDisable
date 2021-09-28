<?php
/**
 * Data helper
 *
 * @category   ZendMonk
 * @package    ZendMonk_EasyDisable
 * @author     Carl Monk <@ZendMonk>
 */
class ZendMonk_EasyDisable_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Config Paths
	 */
	const XML_PATH_CATALOG_SEARCH_DISABLED = 'catalog/search/disabled';
	const XML_PATH_ADVANCED_CATALOG_SEARCH_DISABLED = 'catalog/search/advanced_disabled';
	const XML_PATH_PRODUCT_REVIEWS_DISABLED = 'catalog/review/disabled';
	const XML_PATH_PRODUCT_TAGS_DISABLED = 'catalog/tags/disabled';
	const XML_PATH_PRODUCT_COMPARISON_DISABLED = 'catalog/product_comparison/disabled';
	const XML_PATH_ORDERS_AND_RETURNS_DISABLED = 'sales/orders_returns/disabled';
	const XML_PATH_NEWSLETTER_DISABLED = 'newsletter/general/disabled';
	const XML_PATH_POLLS_DISABLED = 'web/polls/disabled';
	const XML_PATH_EST_SHIPPING_AND_TAX_DISABLED = 'checkout/estimate_shipping_and_tax/disabled';
	const XML_PATH_DISCOUNT_CODES_DISABLED = 'discount_codes/general/disabled';

	const XML_PATH_REMOVE_BREADCRUMBS_FROM_ALL = 'web/breadcrumbs/remove_from_all';
	const XML_PATH_REMOVE_BREADCRUMBS_FROM_PARTICULAR = 'web/breadcrumbs/remove_from_particular';
	const XML_PATH_REMOVE_RECENTLY_VIEWED_COMPARED_SIDEBAR = 'catalog/recently_products/remove_sidebar';
	const XML_PATH_REMOVE_REORDER_SIDEBAR = 'sales/reorder/remove_sidebar';
	const XML_PATH_REMOVE_WISHLIST_LINK = 'wishlist/wishlist_link/remove_from_toplinks';


	protected $_storeConfigFlags = array();

	/**
	 * Retrieve store config flag
	 *
	 * @param string $xmlPath
	 * @return bool
	 */
	protected function _getStoreConfigFlag($xmlPath)
	{
		if (isset($this->_storeConfigFlags[$xmlPath])) {
			return $this->_storeConfigFlags[$xmlPath];
		}
		$this->_storeConfigFlags[$xmlPath] = Mage::getStoreConfigFlag($xmlPath);
		return $this->_storeConfigFlags[$xmlPath];
	}

	/**
	 * Check if Search is disabled
	 *
	 * @return bool
	 */
	public function isSearchDisabled()
	{
		return $this->_getStoreConfigFlag(self::XML_PATH_CATALOG_SEARCH_DISABLED);
	}

	/**
	 * Check if Advanced Search is disabled
	 *
	 * @return bool
	 */
	public function isAdvancedSearchDisabled()
	{
		return $this->_getStoreConfigFlag(self::XML_PATH_ADVANCED_CATALOG_SEARCH_DISABLED);
	}

	/**
	 * Check if Popular Search Terms are disabled
	 *
	 * @return bool
	 */
	public function isPopularTermsDisabled()
	{
		return !Mage::getStoreConfigFlag('catalog/seo/search_terms');
	}

	/**
	 * Check if Reviews are disabled
	 *
	 * @return bool
	 */
	public function isReviewsDisabled()
	{
		return $this->_getStoreConfigFlag(self::XML_PATH_PRODUCT_REVIEWS_DISABLED);
	}

	/**
	 * Check if Tags are disabled
	 *
	 * @return bool
	 */
	public function isTagsDisabled()
	{
		return $this->_getStoreConfigFlag(self::XML_PATH_PRODUCT_TAGS_DISABLED);
	}

	/**
	 * Check if Product Comparison is disabled
	 *
	 * @return bool
	 */
	public function isComparisonDisabled()
	{
		return $this->_getStoreConfigFlag(self::XML_PATH_PRODUCT_COMPARISON_DISABLED);
	}

	/**
	 * Check if Orders and Returns are disabled
	 *
	 * @return bool
	 */
	public function isOrdersReturnsDisabled()
	{
		return $this->_getStoreConfigFlag(self::XML_PATH_ORDERS_AND_RETURNS_DISABLED);
	}

	/**
	 * Check if Newsletter is disabled
	 *
	 * @return bool
	 */
	public function isNewsletterDisabled()
	{
		return $this->_getStoreConfigFlag(self::XML_PATH_NEWSLETTER_DISABLED);
	}

	/**
	 * Check if Polls are disabled
	 *
	 * @return bool
	 */
	public function isPollsDisabled()
	{
		return $this->_getStoreConfigFlag(self::XML_PATH_POLLS_DISABLED);
	}

	/**
	 * Check if Estimate Shipping and Tax is disabled
	 *
	 * @return bool
	 */
	public function isEstShippingAndTaxDisabled()
	{
		return $this->_getStoreConfigFlag(self::XML_PATH_EST_SHIPPING_AND_TAX_DISABLED);
	}

	/**
	 * Check if Discount Codes are disabled
	 *
	 * @return bool
	 */
	public function isDiscountCodesDisabled()
	{
		return $this->_getStoreConfigFlag(self::XML_PATH_DISCOUNT_CODES_DISABLED);
	}

	/**
	 * Check if Breadcrumbs should be removed from current Page
	 *
	 * @return bool
	 */
	public function removeBreadcrumbs()
	{
		if (Mage::getStoreConfig(self::XML_PATH_REMOVE_BREADCRUMBS_FROM_ALL)) {
			return true;
		}
		if (!$storeConfig = Mage::getStoreConfig(self::XML_PATH_REMOVE_BREADCRUMBS_FROM_PARTICULAR)) {
			return false;
		}
		$matchHelper = Mage::helper('zendmonk_easydisable/match');
		if ($removeBreadcrumbsFrom = $matchHelper->getMatchDataRemoveBreadcrumbsFrom($storeConfig)) {
			if ($matchHelper->matchRequest($removeBreadcrumbsFrom)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check if Recently Viewed/Compared Products Sidebar should evt. be removed
	 *
	 * @return bool
	 */
	public function removeRecentlyViewedComparedSidebar()
	{
		return $this->_getStoreConfigFlag(self::XML_PATH_REMOVE_RECENTLY_VIEWED_COMPARED_SIDEBAR);
	}

	/**
	 * Check if Recently Viewed Products Sidebar should be removed
	 *
	 * @return bool
	 */
	public function removeRecentlyViewedProductsSidebar()
	{
		if ($this->removeRecentlyViewedComparedSidebar() &&
			(int) Mage::getStoreConfig(Mage_Reports_Block_Product_Viewed::XML_PATH_RECENTLY_VIEWED_COUNT) == 0) {
			return true;
		}
		return false;
	}

	/**
	 * Check if Recently Compared Products Sidebar should be removed
	 *
	 * @return bool
	 */
	public function removeRecentlyComparedProductsSidebar()
	{
		if ($this->removeRecentlyViewedComparedSidebar() &&
			(int) Mage::getStoreConfig(Mage_Reports_Block_Product_Compared::XML_PATH_RECENTLY_COMPARED_COUNT) == 0) {
			return true;
		}
		return false;
	}

	public function removeReorderSidebar()
	{
		return $this->_getStoreConfigFlag(self::XML_PATH_REMOVE_REORDER_SIDEBAR);
	}

	/**
	 * Check if Wishlist Link should be removed from Toplinks
	 *
	 * @return bool
	 */
	public function removeWishlistLink()
	{
		return $this->_getStoreConfigFlag(self::XML_PATH_REMOVE_WISHLIST_LINK);
	}

	protected $_wishlistEmpty;

	/**
	 * Check if Wishlist is empty
	 *
	 * @return bool
	 */
	public function isWishlistEmpty()
	{
		if (!isset($this->_wishlistEmpty)) {
			$this->_wishlistEmpty = Mage::helper('wishlist')->getWishlist()->getItemsCount() == 0;
		}
		return $this->_wishlistEmpty;
	}

	/**
	 * Check if current Route / Controller / Action is disabled
	 *
	 * @return bool
	 */
	public function isActionDisabled()
	{
		$matchHelper = Mage::helper('zendmonk_easydisable/match');
		if ($actionDisabled = $matchHelper->getMatchDataActionDisabled()) {
			return $matchHelper->matchRequest($actionDisabled);
		}
		return false;
	}

	/**
	 * Check if current Dashboard Route / Controller / Action is disabled
	 *
	 * @return bool
	 */
	public function isDashboardActionDisabled()
	{
		if (Mage::helper('customer')->isLoggedIn()) {
			$matchHelper = Mage::helper('zendmonk_easydisable/match');
			if ($dashboardActionDisabled = $matchHelper->getMatchDataDashboardActionDisabled()) {
				return $matchHelper->matchRequest($dashboardActionDisabled);
			}
		}
		return false;
	}

	/**
	 * Retrieve update handles
	 *
	 * @return array
	 */
	public function getUpdateHandles()
	{
		$updateHandles = array();
		if ($this->isSearchDisabled()) {
			$updateHandles[] = 'catalog_search_disabled';
		}
		if ($this->isReviewsDisabled() && $this->isModuleOutputEnabled('Mage_Review')) {
			$updateHandles[] = 'product_reviews_disabled';
		}
		if ($this->isTagsDisabled() && $this->isModuleOutputEnabled('Mage_Tag')) {
			$updateHandles[] = 'product_tags_disabled';
		}
		if ($this->isComparisonDisabled()) {
			$updateHandles[] = 'product_comparison_disabled';
		}
		if ($this->isNewsletterDisabled() && $this->isModuleOutputEnabled('Mage_Newsletter')) {
			$updateHandles[] = 'newsletter_disabled';
		}
		if ($this->isPollsDisabled() && $this->isModuleOutputEnabled('Mage_Poll')) {
			$updateHandles[] = 'polls_disabled';
		}
		if ($this->removeBreadcrumbs()) {
			$updateHandles[] = 'remove_breadcrumbs';
		}
		$reportsEnabled = $this->isModuleOutputEnabled('Mage_Reports');
		if ($this->removeRecentlyViewedProductsSidebar() && $reportsEnabled) {
			$updateHandles[] = 'remove_recently_viewed_products_sidebar';
		}
		if ($this->removeRecentlyComparedProductsSidebar() && $reportsEnabled) {
			$updateHandles[] = 'remove_recently_compared_products_sidebar';
		}
		if ($this->removeReorderSidebar()) {
			$updateHandles[] = 'remove_reorder_sidebar';
		}
		if ($this->removeWishlistLink() && $this->isWishlistEmpty()) {
			$updateHandles[] = 'remove_wishlist_link';
		}
		return $updateHandles;
	}
}