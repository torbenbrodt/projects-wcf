<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/acp/form/GroupEditForm.class.php');

/**
 * ACP: additional project options in the group-configuration
 * 
 * @author	Torben Brodt
 * @package	de.easy-coding.wcf.projects
 */
class GroupProjectListener implements EventListener {
	protected $project = 0;
	protected $projectIntern = 0;
	protected $projectTrac = 0;

	protected $projectWebsite = "";
	protected $projectSvn = "";
	protected $projectShortName = "";
	protected $groupLeaders = "";
	
	// filled during validation
	protected $projectInternOld = 0;
	protected $projectTracOld = 0;
	protected $projectShortNameOld = "";
	protected $groupLeadersOld = "";
	
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		switch ($eventName) {
		case 'readFormParameters':
			if (isset($_POST['project'])) $this->project = 1;
			if (isset($_POST['projectIntern'])) $this->projectIntern = 1;
			if (isset($_POST['projectTrac'])) $this->projectTrac = 1;

			if (isset($_POST['projectWebsite'])) $this->projectWebsite = $_POST['projectWebsite'];
			if (isset($_POST['projectSvn'])) $this->projectSvn = $_POST['projectSvn'];
			if (isset($_POST['projectShortName'])) $this->projectShortName = $_POST['projectShortName'];
			if (isset($_POST['groupLeaders'])) $this->groupLeaders = $_POST['groupLeaders'];
			break;
		
		
		case 'validate':
			// get old values
			$sql = "SELECT 	*
				FROM 	wcf".WCF_N."_group 
				WHERE 	groupID = {$eventObj->group->groupID}";
			$old = WCF::getDB()->getFirstRow($sql);

			$this->projectInternOld = $old['projectIntern'];
			$this->projectTracOld = $old['projectTrac'];
			$this->projectShortNameOld = $old['projectShortName'];
			$this->groupLeadersOld = '';

			if($this->project) {
				if(empty($this->projectShortName)) {
					throw new UserInputException('projectShortName', 'notUnique');
				}
				
				// is there another project using the same shortname?
				$sql = "SELECT 	COUNT(*) AS c 
					FROM 	wcf".WCF_N."_group 
					WHERE 	projectShortName='{$this->projectShortName}'
					AND	groupID != {$eventObj->group->groupID}";
				$row = WCF::getDB()->getFirstRow($sql);
				if (intval($row['c']) > 0) {
					throw new UserInputException('projectShortName', 'notUnique');
				}
				
				// get group leaders for comparison
				$sql = "SELECT          user.username
					FROM            wcf".WCF_N."_group_leader leader
					INNER JOIN      wcf".WCF_N."_user user
					ON              (leader.userID = user.userID)
					WHERE           leader.groupID = ".$eventObj->group->groupID."
					ORDER BY        user.username";
				$result = WCF::getDB()->sendQuery($sql);
				while ($row = WCF::getDB()->fetchArray($result)) {
					if (!empty($this->groupLeaders)) $this->groupLeaders .= ', ';
					$this->groupLeadersOld .= $row['username'];
				}
			}
			
			// remove
			if($this->projectInternOld && !$this->projectIntern) {
				throw new UserInputException('cannot remove');
			}
			// move
			else if($this->projectInternOld && $this->projectIntern && $this->projectShortNameOld != $this->projectShortName) {
				throw new UserInputException('cannot move');
			}
			break;
		
		
		case 'save':
			$eventObj->additionalFields['project'] = $this->project;
			$eventObj->additionalFields['projectIntern'] = $this->projectIntern;
			$eventObj->additionalFields['projectTrac'] = $this->projectTrac;
			$eventObj->additionalFields['projectWebsite'] = $this->projectWebsite;
			$eventObj->additionalFields['projectSvn'] = $this->projectSvn;
			$eventObj->additionalFields['projectShortName'] = $this->projectShortName;
			break;
		
		
		case 'saved':
			// create new svn environment
			if($this->projectIntern && !$this->projectInternOld) {
				system("ssh 192.168.0.104 project_addsvn {$this->projectShortName}");
			}
			
			// create new trac environment
			if($this->projectTrac && !$this->projectTracOld) {
				system("ssh 192.168.0.104 project_addtrac {$this->projectShortName}");
			}
			
			// give groupleaders special rights
			if($this->projectTrac && $this->groupLeaders != $this->groupLeadersOld) {
				system("ssh 192.168.0.104 project_updatetrac {$this->projectShortName}");
			}
			break;
		
		
		case 'assignVariables':
			if (!count($_POST) && $eventObj instanceof GroupEditForm) {
				$this->project = $eventObj->group->project;
				$this->projectIntern = $eventObj->group->projectIntern;
				$this->projectWebsite = $eventObj->group->projectWebsite;
				$this->projectSvn = $eventObj->group->projectSvn;
				$this->projectShortName = $eventObj->group->projectShortName;
			}
			
			WCF::getTPL()->assign(array(
				'project' => $this->project,
				'projectIntern' => $this->projectIntern,
				'projectWebsite' => $this->projectWebsite,
				'projectSvn' => $this->projectSvn,
				'projectShortName' => $this->projectShortName
			));
			WCF::getTPL()->append('additionalFields', WCF::getTPL()->fetch('groupAddProject'));
			break;
		}
	}
}
?>
