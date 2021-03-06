<?php
// moxeo imports
require_once(MOXEO_DIR.'lib/data/comment/object/CommentableObjectType.class.php');
require_once(MOXEO_DIR.'lib/data/news/NewsItemCommentableObject.class.php');

/**
 * Represents a news item commentable object type.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.moxeo.news
 * @subpackage	data.article.section
 * @category	Moxeo Open Source CMS
 */
class NewsItemCommentableObjectType implements CommentableObjectType {
	/**
	 * @see CommentableObjectType::getObjectByID()
	 */
	public function getObjectByID($objectID) {
		// get object
		$newsItem = new NewsItemCommentableObject($objectID);
		if (!$newsItem->newsItemID) return null;

		// return object
		return $newsItem;
	}

	/**
	 * @see CommentableObjectType::getObjectsByIDs()
	 */
	public function getObjectsByIDs($objectIDs) {
		$newsItems = array();
		$sql = "SELECT	*
			FROM 	moxeo".MOXEO_N."_news_item
			WHERE 	newsItemID IN (".implode(',', $objectIDs).")";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$newsItems[$row['newsItemID']] = new NewsItemCommentableObject(null, $row);
		}

		return (count($newsItems) > 0 ? $newsItems : null);
	}
}
?>