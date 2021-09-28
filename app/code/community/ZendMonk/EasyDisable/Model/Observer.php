<?php
/**
 * Observer
 *
 * @category   ZendMonk
 * @package    ZendMonk_EasyDisable
 * @author     Carl Monk <@ZendMonk>
 */
class ZendMonk_EasyDisable_Model_Observer
{
	/**
	 * Evt. noroute / redirect to Account Dashboard Index on predispatch
	 *
	 * @param Varien_Event_Observer $observer
	 * @return ZendMonk_EasyDisable_Model_Observer
	 */
	public function preDispatch($observer)
	{
		$helper = Mage::helper('zendmonk_easydisable');
		if ($helper->isModuleOutputEnabled()) {
			$action = $observer->getEvent()->getControllerAction();
			if ($helper->isActionDisabled()) {
				$action->norouteAction();
			} else if ($helper->isDashboardActionDisabled()) {
				$action->setRedirectWithCookieCheck('customer/account/');
			}
		}
		return $this;
	}
	
	/**
	 * Add Layout Update Handles before load
	 *
	 * @param Varien_Event_Observer $observer
	 * @return ZendMonk_EasyDisable_Model_Observer
	 */
	public function loadBefore($observer)
	{
		$helper = Mage::helper('zendmonk_easydisable');
		if ($helper->isModuleOutputEnabled()) {
			$update = $observer->getEvent()->getLayout()->getUpdate();
			if ($updateHandles = $helper->getUpdateHandles()) {
				foreach ($updateHandles as $handle) {
					$update->addHandle($handle);
				}
			}
		}
		return $this;
	}
}