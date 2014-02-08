<?php
require_once(WCF_DIR.'lib/page/SortablePage.class.php');
require_once(WCF_DIR.'lib/page/util/menu/HeaderMenu.class.php');

/**
 * Shows a list of all projects.
 * 
 * @author	Torben Brodt
 * @package	de.easy-coding.wcf.page
 */
class ProjectListPage extends SortablePage {
	public $templateName = 'projectList';
	public $itemsPerPage = 20;
	public $defaultSortField = 'groupname';
	public $defaultSortOrder = 'ASC';
	public $searchID = 0;

	protected $projects = array();
	protected $activeFields = array();
	protected $headers = array();
	
	/**
	* @see Page::readParameters()
	*/
	public function readParameters() {
		parent::readParameters();
		
		// active fields
		$this->activeFields = array(
			'groupName',
	                'projectWebsite',
	                'memberCount',
	                'firstActivity',
	                'lastActivity',
	                'revisionCount'
	                );
		
		// search id
		if (isset($_REQUEST['searchID'])) {
			$this->searchID = intval($_REQUEST['searchID']);
			if ($this->searchID != 0) $this->getSearchResult();
		}
	}
        
        /**
         *
         * fetchs activation status
         */
        protected function fetchActivation() {
        	// sql query to fetch revisions
		$sql = "SELECT 		COUNT(*) AS c
			FROM 		wcf".WCF_N."_user_to_groups ug
			NATURAL JOIN 	wcf".WCF_N."_group 
			NATURAL JOIN 	wcf".WCF_N."_user 
			WHERE 		ug.userID = ".WCF::getUser()->userID."
			AND		ISNULL(projectPassword)
			AND 		project = 1 
			AND 		projectIntern = 1";

		$row = WCF::getDB()->getFirstRow($sql);
		return intval($row['c']);
        }
	
	/**
	 * Gets the result of the search with the given search id.
	 */
	protected function getSearchResult() {
		// get user search from database
		$sql = "SELECT	searchData
			FROM	wcf".WCF_N."_search
			WHERE	searchID = ".$this->searchID."
				AND userID = ".WCF::getUser()->userID."
				AND searchType = 'projects'";
		$search = WCF::getDB()->getFirstRow($sql);
		if (!isset($search['searchData'])) {
			require_once(WCF_DIR.'lib/system/exception/IllegalLinkException.class.php');
			throw new IllegalLinkException();
		}
		
		$this->userIDs = implode(',', unserialize($search['searchData']));
	}

	/**
	* @see SortablePage::validateSortField()
	*/
	public function validateSortField() {
		parent::validateSortField();
		
		if(!in_array($this->sortField, $this->activeFields)) {
			$this->sortField = $this->defaultSortField;
                }
	}
	
	/**
	 * Counts the number of projects.
	 * 
	 * @return	integer
	 */
	public function countItems() {
		parent::countItems();

		$sql = "SELECT		COUNT(*) AS count 
			FROM		wcf".WCF_N."_group
			NATURAL JOIN 	wcf".WCF_N."_projectSvn ";
		$row = WCF::getDB()->getFirstRow($sql);

		return $row['count'];
	}
	
	/**
	 * read projects
	 */
	protected function readProjects() {
		$sql = "SELECT		g.groupName,
					g.projectShortName,
					g.projectWebsite,
					(SELECT COUNT(*) FROM wcf".WCF_N."_user_to_groups ug NATURAL JOIN wcf".WCF_N."_user u WHERE ug.groupID = g.groupID AND u.username <> 'guest') AS memberCount,
					(SELECT timestamp FROM wcf".WCF_N."_projectSvn svn WHERE svn.groupID = g.groupID ORDER BY timestamp ASC LIMIT 1) AS firstActivity,
					(SELECT timestamp FROM wcf".WCF_N."_projectSvn svn WHERE svn.groupID = g.groupID ORDER BY timestamp DESC LIMIT 1) AS lastActivity,
					(SELECT COUNT(*) FROM wcf".WCF_N."_projectSvn svn WHERE svn.groupID = g.groupID) AS revisionCount
			FROM			wcf".WCF_N."_group g
			WHERE		g.project = 1
			ORDER BY	".$this->sortField." ".$this->sortOrder;
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$row['groupName'] = sprintf('<a href="%s">%s</a>', 'index.php?page=Project&amp;id='.$row['projectShortName'].SID_ARG_2ND, $row['groupName']);
			$row['firstActivity'] = $row['firstActivity'] ? DateUtil::formatDate(null, $row['firstActivity']) : '-';
			$row['lastActivity'] = $row['lastActivity'] ? DateUtil::formatDate(null, $row['lastActivity']) : '-';
			$row['projectWebsite'] = sprintf('<a href="%s"><img src="'.RELATIVE_WCF_DIR.'icon/websiteM.png" alt="" /></a>', $row['projectWebsite']);
			$this->projects[] = $row;
		}
	}
	
	/**
	 * Gets the list of column headers.
	 */
	protected function loadHeaders() {
		foreach ($this->activeFields as $field) {
			$name = 'wcf.projects.'.$field;
			$this->headers[] = array('field' => $field, 'name' => $name);
		}
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		$this->readProjects();
		$this->loadHeaders();
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// show page
		WCF::getTPL()->assign(array( 
			'allowSpidersToIndexThisPage' => true,
			'projects' => $this->projects,
			'fields' => $this->activeFields,
			'header' => $this->headers,
			'searchID' => $this->searchID
		));
		
		if($this->fetchActivation() > 0) {
			WCF::getTPL()->append('userMessages', '<p class="warning">'.WCF::getLanguage()->get('wcf.projects.passwordActivation.task').'</p>');
		}
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		// set active header menu item
		HeaderMenu::setActiveMenuItem('wcf.header.menu.projects');
		
		parent::show();
	}
}
?>
