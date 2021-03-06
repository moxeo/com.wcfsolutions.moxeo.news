<?php
// moxeo imports
require_once(MOXEO_DIR.'lib/data/news/archive/NewsArchive.class.php');

/**
 * Provides functions to manage news archives.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.moxeo.news
 * @subpackage	data.news.archive
 * @category	Moxeo Open Source CMS
 */
class NewsArchiveEditor extends NewsArchive {
	/**
	 * Creates a new NewsArchiveEditor object.
	 */
	public function __construct($newsArchiveID, $row = null, $cacheObject = null, $useCache = true) {
		if ($useCache) parent::__construct($newsArchiveID, $row, $cacheObject);
		else {
			$sql = "SELECT	*
				FROM	moxeo".MOXEO_N."_news_archive
				WHERE 	newsArchiveID = ".$newsArchiveID;
			$row = WCF::getDB()->getFirstRow($sql);
			parent::__construct(null, $row);
		}
	}

	/**
	 * Updates this news archive.
	 *
	 * @param	string		$title
	 * @param	integer		$contentItemID
	 */
	public function update($title, $contentItemID) {
		$sql = "UPDATE	moxeo".MOXEO_N."_news_archive
			SET	title = '".escapeString($title)."',
				contentItemID = ".$contentItemID."
			WHERE	newsArchiveID = ".$this->newsArchiveID;
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Deletes this news archive.
	 */
	public function delete() {
		// delete news items
		$sql = "DELETE FROM	moxeo".MOXEO_N."_news_item
			WHERE		newsArchiveID = ".$this->newsArchiveID;
		WCF::getDB()->sendQuery($sql);

		// delete news archive
		$sql = "DELETE FROM	moxeo".MOXEO_N."_news_archive
			WHERE		newsArchiveID = ".$this->newsArchiveID;
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Creates a new news archive.
	 *
	 * @param	string		$title
	 * @param	integer		$contentItemID
	 * @return	NewsArchiveEditor
	 */
	public static function create($title, $contentItemID) {
		$sql = "INSERT INTO	moxeo".MOXEO_N."_news_archive
					(title, contentItemID)
			VALUES		('".escapeString($title)."', ".$contentItemID.")";
		WCF::getDB()->sendQuery($sql);

		$newsArchiveID = WCF::getDB()->getInsertID("moxeo".MOXEO_N."_news_archive", 'newsArchiveID');
		return new NewsArchiveEditor($newsArchiveID, null, null, false);
	}

	/**
	 * Resets the news archive cache.
	 */
	public static function resetCache() {
		WCF::getCache()->addResource('newsArchive', MOXEO_DIR.'cache/cache.newsArchive.php', MOXEO_DIR.'lib/system/cache/CacheBuilderNewsArchive.class.php');
		WCF::getCache()->clearResource('newsArchive');
	}
}
?>