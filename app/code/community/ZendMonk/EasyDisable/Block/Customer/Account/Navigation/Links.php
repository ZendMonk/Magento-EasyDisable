<?php
/**
 * Customer Account Navigation Links block
 *
 * @category   ZendMonk
 * @package    ZendMonk_EasyDisable
 * @author     Carl Monk <@ZendMonk>
 */
class ZendMonk_EasyDisable_Block_Customer_Account_Navigation_Links extends Mage_Core_Block_Template
{
	/**
	 * Remove disabled Account Navigation Links
	 *
	 * @return ZendMonk_EasyDisable_Block_Customer_Account_Navigation_Links
	 */
	public function removeDisabled()
	{
		$parentBlock = $this->getParentBlock();
		if ($parentBlock) {
			$matchHelper = Mage::helper('zendmonk_easydisable/match');
			if ($dashboardActionDisabled = $matchHelper->getMatchDataDashboardActionDisabled()) {
				$links = $parentBlock->getLinks();
				foreach ($links as $link) {
					if ($matchHelper->matchRequest($dashboardActionDisabled, $link->getPath())) {
						$parentBlock->removeLinkByName($link->getName());
					}
				}
			}
		}
		return $this;
	}
}
