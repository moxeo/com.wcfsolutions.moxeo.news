<?php
// moxeo imports
require_once(MOXEO_DIR.'lib/data/news/NewsItem.class.php');

/**
 * Provides functions to manage news items.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.moxeo.news
 * @subpackage	data.news
 * @category	Moxeo Open Source CMS
 */
class NewsItemEditor extends NewsItem {
	/**
	 * Updates this news item.
	 *
	 * @param	integer		$userID
	 * @param	string		$title
	 * @param	string		$newsItemAlias
	 * @param	string		$teaser
	 * @param	string		$text
	 * @param	integer		$enableComments
	 * @param	string		$cssID
	 * @param	string		$cssClasses
 	 * @param	string		$publishingStartTime
	 * @param	string		$publishingEndTime
	 */
	public function update($userID, $title, $newsItemAlias, $teaser, $text, $enableComments, $cssID, $cssClasses, $publishingStartTime, $publishingEndTime) {
		$sql = "UPDATE	moxeo".MOXEO_N."_news_item
			SET	userID = ".$userID.",
				title = '".escapeString($title)."',
				newsItemAlias = '".escapeString($newsItemAlias)."',
				teaser = '".escapeString($teaser)."',
				text = '".escapeString($text)."',
				enableComments = ".$enableComments.",
				cssID = '".escapeString($cssID)."',
				cssClasses = '".escapeString($cssClasses)."',
				publishingStartTime = ".$publishingStartTime.",
				publishingEndTime = ".$publishingEndTime."
			WHERE	newsItemID = ".$this->newsItemID;
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Enables this news item.
	 */
	public function enable() {
		$sql = "UPDATE	moxeo".MOXEO_N."_news_item
			SET	enabled = 1
			WHERE	newsItemID = ".$this->newsItemID;
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Disables this news item.
	 */
	public function disable() {
		$sql = "UPDATE	moxeo".MOXEO_N."_news_item
			SET	enabled = 0
			WHERE	newsItemID = ".$this->newsItemID;
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Deletes this news item.
	 */
	public function delete() {
		// get all comment ids
		$commentIDs = '';
		$sql = "SELECT	commentID
			FROM	moxeo".MOXEO_N."_comment
			WHERE	commentableObjectID = ".$this->newsItemID."
				AND commentableObjectType = 'newsItem'";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			if (!empty($commentIDs)) $commentIDs .= ',';
			$commentIDs .= $row['commentID'];
		}
		if (!empty($commentIDs)) {
			// delete comments
			require_once(MOXEO_DIR.'lib/data/comment/CommentEditor.class.php');
			CommentEditor::deleteAll($commentIDs);
		}

		// delete news item
		$sql = "DELETE FROM	moxeo".MOXEO_N."_news_item
			WHERE		newsItemID = ".$this->newsItemID;
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Creates a new news item.
	 *
	 * @param	integer		$newsArchiveID
	 * @param	integer		$userID
	 * @param	string		$title
	 * @param	string		$newsItemAlias
	 * @param	string		$teaser
	 * @param	string		$text
	 * @param	integer		$enableComments
	 * @param	string		$cssID
	 * @param	string		$cssClasses
	 * @param	string		$publishingStartTime
	 * @param	string		$publishingEndTime
	 * @return	NewsItemEditor
	 */
	public static function create($newsArchiveID, $userID, $title, $newsItemAlias, $teaser, $text, $enableComments, $cssID, $cssClasses, $publishingStartTime, $publishingEndTime) {
		$sql = "INSERT INTO	moxeo".MOXEO_N."_news_item
					(newsArchiveID, userID, title, newsItemAlias, cssID, cssClasses, teaser, text, enableComments, time, publishingStartTime, publishingEndTime, enabled)
			VALUES		(".$newsArchiveID.", ".$userID.", '".escapeString($title)."', '".escapeString($newsItemAlias)."', '".escapeString($cssID)."', '".escapeString($cssClasses)."', '".escapeString($teaser)."', '".escapeString($text)."', ".$enableComments.", ".TIME_NOW.", '".escapeString($publishingStartTime)."', '".escapeString($publishingEndTime)."', ".intval(WCF::getUser()->getPermission('admin.moxeo.canEnableNewsItem')).")";
		WCF::getDB()->sendQuery($sql);

		$newsItemID = WCF::getDB()->getInsertID("moxeo".MOXEO_N."_news_item", 'newsItemID');
		return new NewsItemEditor($newsItemID);
	}
}
?>