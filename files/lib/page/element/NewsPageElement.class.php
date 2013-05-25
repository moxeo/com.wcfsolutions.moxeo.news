<?php
// moxeo imports
require_once(MOXEO_DIR.'lib/data/news/NewsItemList.class.php');
require_once(MOXEO_DIR.'lib/data/news/archive/NewsArchive.class.php');

// wcf imports
require_once(WCF_DIR.'lib/page/element/ThemeModulePageElement.class.php');

/**
 * Represents a news element.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.moxeo.news
 * @subpackage	page.element
 * @category	Moxeo Open Source CMS
 */
class NewsPageElement extends ThemeModulePageElement {
	// system
	public $templateName = 'newsItemList';

	/**
	 * news item list object
	 *
	 * @var	NewsItemList
	 */
	public $newsItemList = null;

	/**
	 * content item object
	 *
	 * @var	ContentItem
	 */
	public $contentItem = null;

	/**
	 * news item alias
	 *
	 * @var	string
	 */
	public $newsItemAlias = '';

	/**
	 * news item object
	 *
	 * @var	NewsItem
	 */
	public $newsItem = null;

	/**
	 * news archive object
	 *
	 * @var	NewsArchive
	 */
	public $newsArchive = null;

	/**
	 * list of comments
	 *
	 * @var	CommentList
	 */
	public $commentList = null;

	/**
	 * @see	Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		$this->newsItemAlias = ContentItemRequestHandler::getInstance()->getFilename();
		if ($this->newsItemAlias) {
			// get news item
			$this->newsItem = NewsItem::getNewsItemByAlias($this->newsItemAlias);
			if (!$this->newsItem->newsItemID) {
				throw new IllegalLinkException();
			}

			// get news archive
			$this->newsArchive = new NewsArchive($this->newsItem->newsArchiveID);
			$this->newsItem->enter();

			// check news archive
			if (!in_array($this->newsArchive->newsArchiveID, $this->themeModule->newsArchiveIDs)) {
				throw new IllegalLinkException();
			}

			// init comment list
			if ($this->newsItem->enableComments) {
				require_once(MOXEO_DIR.'lib/data/comment/CommentList.class.php');
				$this->commentList = new CommentList();
				$this->commentList->sqlConditions .= "comment.commentableObjectID = ".$this->newsItem->newsItemID." AND comment.commentableObjectType = 'newsItem'";
				$this->commentList->sqlOrderBy = 'comment.time DESC';
			}
		}
		else {
			// init news item list
			$this->newsItemList = new NewsItemList();
			$this->newsItemList->sqlConditions = "	news_item.newsArchiveID IN (".implode(',', $this->themeModule->newsArchiveIDs).")
								AND news_item.enabled = 1
								AND (news_item.publishingStartTime = 0 OR news_item.publishingStartTime > ".TIME_NOW.")
								AND (news_item.publishingEndTime = 0 OR news_item.publishingEndTime <= ".TIME_NOW.")";

			// get content item
			if (!isset($this->additionalData['contentItem'])) {
				throw new SystemException('no content item given');
			}
			$this->contentItem = $this->additionalData['contentItem'];

			// news items per page
			if ($this->themeModule->newsItemsPerPage) $this->itemsPerPage = $this->themeModule->newsItemsPerPage;
		}
	}

	/**
	 * @see	MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();

		if ($this->newsItemList !== null) {
			return $this->newsItemList->countObjects();
		}
		return $this->commentList->countObjects();
	}

	/**
	 * @see	Page::readData()
	 */
	public function readData() {
		parent::readData();

		if ($this->newsItemList !== null) {
			// read news items
			$this->newsItemList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
			$this->newsItemList->sqlLimit = $this->itemsPerPage;
			$this->newsItemList->readObjects();
		else {
			// read comments
			$this->commentList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
			$this->commentList->sqlLimit = $this->itemsPerPage;
			$this->commentList->readObjects();
		}
	}

	/**
	 * @see	Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		// init comment add form
		if ($this->commentList !== null) {
			require_once(MOXEO_DIR.'lib/form/element/CommentAddFormElement.class.php');
			$commentAddForm = new CommentAddFormElement($this->newsItem->getCommentableObject(), $this->additionalData['contentItem'], $this->newsItem->getURL());
		}

		WCF::getTPL()->assign(array(
			'contentItem' => $this->contentItem,
			'newsItems' => ($this->newsItemList !== null ? $this->newsItemList->getObjects() : array()),
			'newsArchive' => $this->newsArchive,
			'newsItem' => $this->newsItem,
			'newsItemAlias' => $this->newsItemAlias,
			'comments' => ($this->commentList !== null ? $this->commentList->getObjects() : array()),
			'commentForm' => ($this->commentList !== null ? $commentAddForm->getContent() : '')
		));
	}
}
?>