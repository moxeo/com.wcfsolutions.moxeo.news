<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Updates news archives after content item deletion.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.moxeo.news
 * @subpackage	system.event.listener
 * @category	Moxeo Open Source CMS
 */
class ContentItemDeleteActionNewsArchiveListener implements EventListener {
	/**
	 * @see	EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		// update news archives
		$sql = "UPDATE	moxeo".MOXEO_N."_news_archive
			SET	contentItemID = 0
			WHERE	contentItemID = ".$eventObj->contentItemID;
		WCF::getDB()->sendQuery($sql);
	}
}
?>