<?php
/**
 * Match helper
 *
 * @category   ZendMonk
 * @package    ZendMonk_EasyDisable
 * @author     Carl Monk <@ZendMonk>
 */
class ZendMonk_EasyDisable_Helper_Match extends Mage_Core_Helper_Abstract
{
	protected $_currentRequestKeys;
	
	/**
	 * Retrieve Names of current Route / Controller / Action
	 *
	 * @return array
	 */
	protected function _getCurrentRequestKeys()
	{
		if (isset($this->_currentRequestKeys)) {
			return $this->_currentRequestKeys;
		}
		$request = Mage::app()->getRequest();
		$requestKeys = array(
			$request->getRouteName(),
			$request->getControllerName(),
			$request->getActionName()
		);
		$this->_currentRequestKeys = $requestKeys;
		return $requestKeys;
	}
	
	/**
	 * Check if Array Data matches current Request
	 *
	 * @param string $matchData
	 * @return bool
	 */
	public function matchRequest($matchData, $requestPath = null)
	{
		if (!isset($requestPath)) {
			$requestKeys = $this->_getCurrentRequestKeys();
		} else {
			$requestKeys = explode('/', trim($requestPath, '/'));
		}
		$matches = true;
		foreach ($requestKeys as $requestKey) {
			if (!isset($matchData[$requestKey])) {
				$matches = false;
				break;
			} else {
				if (!is_array($matchData[$requestKey])) {
					break;
				}
				$matchData = $matchData[$requestKey];
			}
		}
		$this->_matchRequest[$requestPath] = $matches;
		return $matches;
	}
	
	protected $_matchDataActionDisabled;
	
	/**
	 * Retrieve Match Data to check if Route / Controller / Action is disabled
	 *
	 * @return array
	 */
	public function getMatchDataActionDisabled()
	{
		if (isset($this->_matchDataActionDisabled)) {
			return $this->_matchDataActionDisabled;
		}
		
		$helper = Mage::helper('zendmonk_easydisable');
		$catalogSearch = $helper->isSearchDisabled() ? array('result' => 1, 'ajax' => 1) : array();
		if ($helper->isAdvancedSearchDisabled()) {
			$catalogSearch['advanced'] = 1;
		}
		if ($helper->isPopularTermsDisabled()) {
			$catalogSearch['term'] = 1;
		}
		$matchData = $catalogSearch ? array('catalogsearch' => $catalogSearch) : array();
		if ($helper->isReviewsDisabled()) {
			$matchData['review'] = 1;
		}
		if ($helper->isTagsDisabled()) {
			$matchData['tag'] = 1;
		}
		$catalog = array();
		if ($helper->isComparisonDisabled()) {
			$catalog['product_compare'] = 1;
		}
		if (!Mage::getStoreConfig('catalog/seo/site_map')) {
			$catalog['seo_sitemap'] = 1;
		}
		if ($catalog) {
			$matchData['catalog'] = $catalog;
		}
		if ($helper->isOrdersReturnsDisabled()) {
			$matchData['sales'] = array('guest' => array('form' => 1, 'view' => 1));
		}
		if ($helper->isNewsletterDisabled()) {
			$matchData['newsletter'] = 1;
		}
		if ($helper->isPollsDisabled()) {
			$matchData['poll'] = 1;
		}
		$cart = array();
		if ($helper->isEstShippingAndTaxDisabled()) {
			$cart = array('estimatePost' => 1, 'estimateUpdatePost' => 1);
		}
		if ($helper->isDiscountCodesDisabled()) {
			$cart['couponPost'] = 1;
		}
		if ($cart) {
			$matchData['checkout'] = array('cart' => $cart);
		}
		$this->_matchDataActionDisabled = $matchData;
		return $matchData;
	}
	
	protected $_matchDataDashboardActionDisabled;
	
	/**
	 * Retrieve Match Data to check if Dashboard Route / Controller / Action is disabled
	 *
	 * @return array
	 */
	public function getMatchDataDashboardActionDisabled()
	{
		if (isset($this->_matchDataDashboardActionDisabled)) {
			return $this->_matchDataDashboardActionDisabled;
		}
		
		$dashboardHelper = Mage::helper('zendmonk_easydisable/dashboard');
		$sales = array();
		if ($dashboardHelper->removeMyOrders()) {
			$sales['order'] = 1;
		}
		if ($dashboardHelper->removeBillingAgreements()) {
			$sales['billing_agreement'] = 1;
		}
		if ($dashboardHelper->removeRecurringProfiles()) {
			$sales['recurring_profile'] = 1;
		}
		$matchData = $sales ? array('sales' => $sales) : array();
		if ($dashboardHelper->removeMyProductReviews()) {
			$matchData['review'] = array('customer' => 1);
		}
		if ($dashboardHelper->removeMyTags()) {
			$matchData['tag'] = array('customer' => 1);
		}
		if ($dashboardHelper->removeMyWishlist()) {
			$matchData['wishlist'] = array('index' => array('index' => 1, 'configure' => 1, 'updateItemOptions' => 1, 'update' => 1, 'remove' => 1, 'cart' => 1, 'share' => 1, 'send' => 1, 'downloadCustomOptions' => 1), 'shared' => 1);
		}
		if ($dashboardHelper->removeMyApplications()) {
			$matchData['oauth'] = array('customer_token' => 1);
		}
		if ($dashboardHelper->removeNewsletterSubscriptions()) {
			$matchData['newsletter'] = array('manage' => 1);
		}
		if ($dashboardHelper->removeMyDownloadableProducts()) {
			$matchData['downloadable'] = array('customer' => 1);
		}
		$this->_matchDataDashboardActionDisabled = $matchData;
		return $matchData;
	}
	
	/**
	 * Retrieve Match Data to check if Breadcrumbs should be removed from current Page
	 *
	 * @param string $storeConfig
	 * @return array
	 */
	public function getMatchDataRemoveBreadcrumbsFrom($storeConfig)
	{
		$matchData = array();
		foreach (explode(',', $storeConfig) as $removePathObject) {
			$removePath = explode('/', $removePathObject);
			$routeName = $removePath[0];
			if (!isset($removePath[1])) {
				$matchData[$routeName] = 1;
				continue;
			}
			if (!isset($matchData[$routeName])) {
				$matchData[$routeName] = array();
			}
			
			$controllerName = $removePath[1];
			if (!isset($removePath[2])) {
				$matchData[$routeName][$controllerName] = 1;
				continue;
			}
			if (!isset($matchData[$routeName][$controllerName])) {
				$matchData[$routeName][$controllerName] = array();
			}
			
			$actionName = $removePath[2];
			$matchData[$routeName][$controllerName][$actionName] = 1;
		}
		return $matchData;
	}
}