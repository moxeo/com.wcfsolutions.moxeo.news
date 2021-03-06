<?php
// moxeo imports
require_once(MOXEO_DIR.'lib/data/news/archive/NewsArchive.class.php');

// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a news item.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.moxeo.news
 * @subpackage	data.news
 * @category	Moxeo Open Source CMS
 */
class NewsItem extends DatabaseObject {
	/**
	 * news archive object
	 *
	 * @var	NewsArchive
	 */
	protected $archive = null;

	/**
	 * Creates a new NewsItem object.
	 *
	 * @param	integer		$newsItemID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($newsItemID, $row = null) {
		if ($newsItemID !== null) {
			$sql = "SELECT		news_item.*, user_table.username
				FROM		moxeo".MOXEO_N."_news_item news_item
				LEFT JOIN	wcf".WCF_N."_user user_table
				ON		(user_table.userID = news_item.userID)
				WHERE 		news_item.newsItemID = ".$newsItemID;
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}

	/**
	 * Enters this news item.
	 */
	public function enter() {
		// check permissions
		if (!$this->enabled || !$this->isPublished()) {
			throw new PermissionDeniedException();
		}
	}

	/**
	 * Returns true, if this news item is published.
	 *
	 * @return	boolean
	 */
	public function isPublished() {
		if ($this->publishingStartTime && $this->publishingStartTime > TIME_NOW) {
			return false;
		}
		if ($this->publishingEndTime && $this->publishingEndTime <= TIME_NOW) {
			return false;
		}
		return true;
	}

	/**
	 * Returns the formatted tesaer.
	 *
	 * @return	string
	 */
	public function getFormattedTeaser() {
		return nl2br(StringUtil::encodeHTML($this->teaser));
	}

	/**
	 * Returns the news archive object of this news item.
	 *
	 * @return	NewsArchive
	 */
	public function getArchive() {
		if ($this->archive === null) {
			$this->archive = new NewsArchive($this->newsArchiveID);
		}
		return $this->archive;
	}

	/**
	 * Returns the url of this news item.
	 *
	 * @return	string
	 */
	public function getURL() {
		if ($this->getArchive()->contentItemID) {
			return $this->getArchive()->getContentItem()->getURL().$this->newsItemAlias.'.html';
		}
		return '';
	}

	/**
	 * Returns an commmentable object object for this news item.
	 *
	 * @return	NewsItemCommentableObject
	 */
	public function getCommentableObject() {
		require_once(MOXEO_DIR.'lib/data/news/NewsItemCommentableObject.class.php');
		return new NewsItemCommentableObject(null, $this->data);
	}

	/**
	 * Returns the news item object with the given news item alias.
	 *
	 * @param	string		$newsItemAlias
	 * @return	NewsItem
	 */
	public static function getNewsItemByAlias($newsItemAlias) {
		$sql = "SELECT		news_item.*, user_table.username
			FROM		moxeo".MOXEO_N."_news_item news_item
			LEFT JOIN	wcf".WCF_N."_user user_table
			ON		(user_table.userID = news_item.userID)
			WHERE 		news_item.newsItemAlias = '".escapeString($newsItemAlias)."'";
		$row = WCF::getDB()->getFirstRow($sql);
		return new NewsItem(null, $row);
	}
}
?>