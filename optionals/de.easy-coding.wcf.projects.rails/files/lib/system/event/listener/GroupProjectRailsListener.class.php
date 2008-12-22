<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/acp/form/GroupEditForm.class.php');

/**
 * ACP: allow to add rails projects
 * 
 * @author	Torben Brodt
 * @package	de.easy-coding.wcf.projects.rails
 */
class GroupProjectRailsListener implements EventListener {
	protected $projectRails = 0;
	
	// filled during validation
	protected $projectRailsOld = 0;
	
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		switch ($eventName) {
		case 'readFormParameters':
			if (isset($_POST['project'])) $this->project = 1;
			if (isset($_POST['projectRails'])) $this->projectRails = 1;
			break;
		
		
		case 'validate':
			if($eventObj->group) {
				// get old values
				$sql = "SELECT 	*
					FROM 	wcf".WCF_N."_group 
					WHERE 	groupID = {$eventObj->group->groupID}";
				$old = WCF::getDB()->getFirstRow($sql);
				$this->projectRailsOld = $old['projectRails'];
			}
			
			// remove rails
			if($this->projectRailsOld && !$this->projectRails) {
				throw new UserInputException('cannot remove rails');
			}
			// move rails
			else if($this->projectRailsOld && $this->projectRails && $this->projectShortNameOld != $this->projectShortName) {
				throw new UserInputException('cannot move rails');
			}
			break;
		
		
		case 'save':
			$eventObj->additionalFields['projectRails'] = $this->projectRails;
			break;
		
		
		case 'saved':
			// create new rails environment
			if($this->projectRails && !$this->projectRailsOld) {
				system("ssh 192.168.0.105 project_addRails {$this->projectShortName}");
			}
			break;
		
		
		case 'assignVariables':
			if (!count($_POST) && $eventObj instanceof GroupEditForm) {
				$this->projectRails = $eventObj->group->projectRails;
			}
			
			WCF::getTPL()->assign(array(
				'projectRails' => $this->projectRails
			));
			WCF::getTPL()->append('additionalProjectOptions', WCF::getTPL()->fetch('groupAddProjectRails'));
			break;
		}
	}
}
?>
