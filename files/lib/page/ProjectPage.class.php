<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/page/util/menu/HeaderMenu.class.php');

/**
 * Shows details of one project...
 * 
 * @author	Torben Brodt
 * @package	de.easy-coding.wcf.page
 */
class ProjectPage extends AbstractPage {
	// has to be set, otherwise the event StructuredTemplate::shouldDisplay is not called and seo links are not rewritten
	public $templateName = 'project';
	 
	// param
	protected $shortName = '';
	
	// data
	protected $general = array();
	protected $licenses = array();
	protected $revisions = array();
	protected $members = array();
	

        /**
         * @see Page::readParameters()
         */
        public function readParameters() {
                parent::readParameters();

                $this->shortName = isset($_GET['id']) ? $_GET['id'] : '';
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
         *
         * @param groupID
         */
       	protected function fetchMembers($groupID) {
        	// sql query to fetch revisions
		$sq1 = "SELECT		u.userID,
					u.username,
					(SELECT COUNT(*) FROM wcf".WCF_N."_group_leader l WHERE l.groupID = ug.groupID AND l.userID = u.userID) AS isLeader,
					(SELECT COUNT(*) FROM wcf".WCF_N."_projectSvn svn WHERE svn.groupID = ug.groupID AND svn.author = u.username) AS commits
			FROM		wcf".WCF_N."_user_to_groups ug
			NATURAL JOIN	wcf".WCF_N."_user u
			WHERE		ug.groupID = ".$groupID."
			AND		u.username <> 'guest' ";
			
		$sq2 = "SELECT		svn.userID,
					svn.author AS username,
					(SELECT COUNT(*) FROM wcf1_group_leader l WHERE l.groupID = svn.groupID AND l.userID = svn.userID) AS isLeader,
					COUNT(svn.revision) AS commits
			FROM		wcf1_projectSvn svn
			WHERE		svn.author <> 'root'
			AND		svn.groupID = ".$groupID." 
			GROUP BY	svn.author";
			
		$sql = "SELECT		X.userID, 
					X.username, 
					X.isLeader, 
					SUM(X.commits) AS commits
			FROM		($sq1 UNION $sq2) X
			GROUP BY	X.username
			ORDER BY	X.isLeader DESC, X.username ASC;";

		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->members[] = $row;
		}
        }
 
        /**
         *
         * @param groupID
         */
        protected function fetchLicenses($groupID) {
		// sql query to fetch revisions
		$sql = "SELECT		licenseName,
					licenseURL
			FROM		wcf".WCF_N."_group_to_licenses gl
			NATURAL JOIN	wcf".WCF_N."_projectLicenses l
			WHERE		gl.groupID = ".$groupID."
			ORDER BY	licenseName ASC; ";

		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->licenses[] = $row;
		}
        }
        
        /**
         *
         * @param groupID
         */
        protected function fetchRevisions($groupID) {
		// sql query to fetch revisions
		$sql = "SELECT		revision,
					userID,
					author,
					timestamp,
					msg
			FROM		wcf".WCF_N."_projectSvn svn
			WHERE		groupID = ".$groupID."
			ORDER BY	revision DESC
			LIMIT		20; ";

		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->revisions[] = $row;
		}
        }
        
        /**
         *
         * @param shortName
         */
        protected function fetchProject($shortName) {
        	$sql = "SELECT		g.groupID,
        				g.groupName,
					g.projectShortName,
					g.projectWebsite,
					(SELECT COUNT(*) FROM wcf".WCF_N."_user_to_groups ug NATURAL JOIN wcf".WCF_N."_user u WHERE ug.groupID = g.groupID AND u.username <> 'guest') AS memberCount,
					(SELECT timestamp FROM wcf".WCF_N."_projectSvn svn WHERE svn.groupID = g.groupID ORDER BY timestamp ASC LIMIT 1) AS firstActivity,
					(SELECT timestamp FROM wcf".WCF_N."_projectSvn svn WHERE svn.groupID = g.groupID ORDER BY timestamp DESC LIMIT 1) AS lastActivity,
					(SELECT COUNT(*) FROM wcf".WCF_N."_projectSvn svn WHERE svn.groupID = g.groupID) AS revisionCount
			FROM		wcf".WCF_N."_group g
			WHERE		g.project = 1
			AND		g.projectShortName = '".$shortName."' ";

		$this->general = WCF::getDB()->getFirstRow($sql);
        }
        
        /**
         * @see Page::readData()
         */
        public function readData() {
		parent::readData();

		$this->fetchProject($this->shortName);
		if(!$this->general) {
			require_once(WCF_DIR.'lib/system/exception/PermissionDeniedException.class.php');
			throw new PermissionDeniedException();
		}
		
		$this->fetchMembers($this->general['groupID']);
		$this->fetchLicenses($this->general['groupID']);
		$this->fetchRevisions($this->general['groupID']);
        }

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		// show page
		WCF::getTPL()->assign(array(
			'general' => $this->general,
			'revisions' => $this->revisions,
			'members' => $this->members,
			'licenses' => $this->licenses,
			'allowSpidersToIndexThisPage' => true
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
