<?php
/**
 * Dashboard helper
 *
 * @category   ZendMonk
 * @package    ZendMonk_EasyDisable
 * @author     Carl Monk <@ZendMonk>
 */
class ZendMonk_EasyDisable_Helper_Dashboard extends Mage_Core_Helper_Abstract
{
	/**
	 * Config Paths
	 */
	const XML_PATH_REMVOE_LOGIN_WELCOME_MESSAGE_FROM_DASHBOARD = 'customer_dashboard/general/remove_login_welcome_msg';
	const XML_PATH_REMOVE_MY_ORDERS_FROM_DASHBOARD = 'customer_dashboard/remove/orders';
	const XML_PATH_REMOVE_BILLING_AGREEMENTS_FROM_DASHBOARD = 'customer_dashboard/remove/billing_agreements';
	const XML_PATH_REMOVE_RECURRING_PROFILES_FROM_DASHBOARD = 'customer_dashboard/remove/recurring_profiles';
	const XML_PATH_REMOVE_MY_PRODUCT_REVIEWS_FROM_DASHBOARD = 'customer_dashboard/remove/review';
	const XML_PATH_REMOVE_MY_TAGS_FROM_DASHBOARD = 'customer_dashboard/remove/tags';
	const XML_PATH_REMOVE_MY_WISHLIST_FROM_DASHBOARD = 'customer_dashboard/remove/wishlist';
	const XML_PATH_REMOVE_MY_APPLICATIONS_FROM_DASHBOARD = 'customer_dashboard/remove/applications';
	const XML_PATH_REMOVE_NEWSLETTER_SUBSCRIPTIONS_FROM_DASHBOARD = 'customer_dashboard/remove/newsletter_subscriptions';
	const XML_PATH_REMOVE_MY_DOWNLOADABLE_PRODUCTS_FROM_DASHBOARD = 'customer_dashboard/remove/downloadable';
	
	protected $_customer;
	
	/**
	 * Retrieve logged in customer
	 *
	 * @return Mage_Customer_Model_Customer
	 */
	protected function _getCustomer()
	{
		if (!isset($this->_customer)) {
			 $this->_customer = Mage::helper('customer')->getCustomer();
		}
		return $this->_customer;
	}
	
	protected $_removeFromDashboardFlags = array();
	
	/**
	 * Retrieve Remove from Dashboard flag
	 *
	 * @param string $xmlPath
	 * @return bool
	 */
	protected function _getRemoveFromDashboardFlag($xmlPath)
	{
		if (isset($this->_removeFromDashboardFlags[$xmlPath])) {
			return $this->_removeFromDashboardFlags[$xmlPath];
		}
		if (!Mage::helper('customer')->isLoggedIn()) {
			$remove = false;
		} else {
			$config = (int) Mage::getStoreConfig($xmlPath);
			if ($config != 1) {
				$remove = (bool) $config;
			}
		}
		if (isset($remove)) {
			$this->_removeFromDashboardFlags[$xmlPath] = $remove;
			return $remove;
		}
		return null;
	}
	
	/**
	 * Check if Login Welcome Message should evt. be removed from Dashboard Header
	 *
	 * @return bool
	 */
	public function removeLoginWelcomeMessage()
	{
		if (Mage::helper('customer')->isLoggedIn()) {
			return Mage::getStoreConfigFlag(self::XML_PATH_REMVOE_LOGIN_WELCOME_MESSAGE_FROM_DASHBOARD);
		}
		return false;
	}
	
	/**
	 * Check if My Orders should evt. be removed from Dashboard
	 *
	 * @return bool
	 */
	public function removeMyOrders()
	{
		$xmlPath = self::XML_PATH_REMOVE_MY_ORDERS_FROM_DASHBOARD;
		if (!is_null($this->_getRemoveFromDashboardFlag($xmlPath))) {
			return $this->_getRemoveFromDashboardFlag($xmlPath);
		}
		$orders = Mage::getResourceModel('sales/order_collection')
            ->addFieldToFilter('customer_id', $this->_getCustomer()->getId())
            ->addFieldToFilter('state', array('in' => Mage::getSingleton('sales/order_config')->getVisibleOnFrontStates()));
		$remove = !$orders->getSize();
		$this->_removeFromDashboardFlags[$xmlPath] = $remove;
		return $remove;
	}
	
	/**
	 * Check if Billing Agreements should evt. / always be removed from Dashboard
	 *
	 * @return bool
	 */
	public function removeBillingAgreements()
	{
		$xmlPath = self::XML_PATH_REMOVE_BILLING_AGREEMENTS_FROM_DASHBOARD;
		if (!is_null($this->_getRemoveFromDashboardFlag($xmlPath))) {
			return $this->_getRemoveFromDashboardFlag($xmlPath);
		}
		$billingAgreements = Mage::getResourceModel('sales/billing_agreement_collection')
			->addFieldToFilter('customer_id', $this->_getCustomer()->getId());
		$remove = !$billingAgreements->getSize();
		$this->_removeFromDashboardFlags[$xmlPath] = $remove;
		return $remove;
	}
	
	/**
	 * Check if Recurring Profiles should evt. / always be removed from Dashboard
	 *
	 * @return bool
	 */
	public function removeRecurringProfiles()
	{
		$xmlPath = self::XML_PATH_REMOVE_RECURRING_PROFILES_FROM_DASHBOARD;
		if (!is_null($this->_getRemoveFromDashboardFlag($xmlPath))) {
			return $this->_getRemoveFromDashboardFlag($xmlPath);
		}
		$recurringProfiles = Mage::getModel('sales/recurring_profile')
			->getCollection()
            ->addFieldToFilter('customer_id', $this->_getCustomer()->getId());
		$remove = !$recurringProfiles->getSize();
		$this->_removeFromDashboardFlags[$xmlPath] = $remove;
		return $remove;
	}
	
	/**
	 * Check if My Product Reviews should evt. be removed from Dashboard
	 *
	 * @return bool
	 */
	public function removeMyProductReviews()
	{
		$xmlPath = self::XML_PATH_REMOVE_MY_PRODUCT_REVIEWS_FROM_DASHBOARD;
		if (!isset($this->_removeFromDashboardFlags[$xmlPath]) && Mage::helper('zendmonk_easydisable')->isReviewsDisabled()) {
			$this->_removeFromDashboardFlags[$xmlPath] = false;
		}
		if (!is_null($this->_getRemoveFromDashboardFlag($xmlPath))) {
			return $this->_getRemoveFromDashboardFlag($xmlPath);
		}
		$reviews = Mage::getModel('review/review')
			->getProductCollection()
            ->addStoreFilter(Mage::app()->getStore()->getId())
            ->addCustomerFilter($this->_getCustomer()->getId());
		$remove = !$reviews->getSize();	
		$this->_removeFromDashboardFlags[$xmlPath] = $remove;
		return $remove;
	}
	
	/**
	 * Check if My Tags should evt. be removed from Dashboard
	 *
	 * @return bool
	 */
	public function removeMyTags()
	{
		$xmlPath = self::XML_PATH_REMOVE_MY_TAGS_FROM_DASHBOARD;
		if (!isset($this->_removeFromDashboardFlags[$xmlPath]) && Mage::helper('zendmonk_easydisable')->isTagsDisabled()) {
			$this->_removeFromDashboardFlags[$xmlPath] = false;
		}
		if (!is_null($this->_getRemoveFromDashboardFlag($xmlPath))) {
			return $this->_getRemoveFromDashboardFlag($xmlPath);
		}
		$tags = Mage::getResourceModel('tag/tag_collection')
			->addPopularity(null, Mage::app()->getStore()->getId())
        	->addCustomerFilter($this->_getCustomer()->getId())
			->setActiveFilter();
		$remove = !$tags->getSize();	
		$this->_removeFromDashboardFlags[$xmlPath] = $remove;
		return $remove;
	}
	
	/**
	 * Check if My Wishlist should evt. be removed from Dashboard
	 *
	 * @return bool
	 */
	public function removeMyWishlist()
	{
		$xmlPath = self::XML_PATH_REMOVE_MY_WISHLIST_FROM_DASHBOARD;
		if (!is_null($this->_getRemoveFromDashboardFlag($xmlPath))) {
			return $this->_getRemoveFromDashboardFlag($xmlPath);
		}
		$remove = Mage::helper('zendmonk_easydisable')->isWishlistEmpty();
		$this->_removeFromDashboardFlags[$xmlPath] = $remove;
		return $remove;
	}
	
	/**
	 * Check if My Applications should evt. / always be removed from Dashboard
	 *
	 * @return bool
	 */
	public function removeMyApplications()
	{
		$xmlPath = self::XML_PATH_REMOVE_MY_APPLICATIONS_FROM_DASHBOARD;
		if (!is_null($this->_getRemoveFromDashboardFlag($xmlPath))) {
			return $this->_getRemoveFromDashboardFlag($xmlPath);
		}
		$applications = Mage::getModel('oauth/token')
			->getCollection()
 			->joinConsumerAsApplication()
            ->addFilterByType(Mage_Oauth_Model_Token::TYPE_ACCESS)
            ->addFilterByCustomerId($this->_getCustomer()->getId());
		$remove = !$applications->getSize();
		$this->_removeFromDashboardFlags[$xmlPath] = $remove;
		return $remove;
	}
	
	/**
	 * Check if Newsletter Subscriptions should evt. / always be removed from Dashboard
	 *
	 * @return bool
	 */
	public function removeNewsletterSubscriptions()
	{
		$xmlPath = self::XML_PATH_REMOVE_NEWSLETTER_SUBSCRIPTIONS_FROM_DASHBOARD;
		if (!isset($this->_removeFromDashboardFlags[$xmlPath]) && Mage::helper('zendmonk_easydisable')->isNewsletterDisabled()) {
			$this->_removeFromDashboardFlags[$xmlPath] = false;
		}
		if (!is_null($this->_getRemoveFromDashboardFlag($xmlPath))) {
			return $this->_getRemoveFromDashboardFlag($xmlPath);
		}
		$isSubscribed = Mage::getModel('newsletter/subscriber')
			->loadByCustomer($this->_getCustomer())
			->isSubscribed();
		$remove = !$isSubscribed;
		$this->_removeFromDashboardFlags[$xmlPath] = $remove;
		return $remove;
	}
	
	/**
	 * Check if My Downloadable Products should evt. / always be removed from Dashboard
	 *
	 * @return bool
	 */
	public function removeMyDownloadableProducts()
	{
		$xmlPath = self::XML_PATH_REMOVE_MY_DOWNLOADABLE_PRODUCTS_FROM_DASHBOARD;
		if (!is_null($this->_getRemoveFromDashboardFlag($xmlPath))) {
			return $this->_getRemoveFromDashboardFlag($xmlPath);
		}
		$remove = false;
		$downloadableProducts = Mage::getResourceModel('downloadable/link_purchased_collection')
            ->addFieldToFilter('customer_id', $this->_getCustomer()->getId());
		$remove = !$downloadableProducts->getSize();
		$this->_removeFromDashboardFlags[$xmlPath] = $remove;
		return $remove;
	}
}