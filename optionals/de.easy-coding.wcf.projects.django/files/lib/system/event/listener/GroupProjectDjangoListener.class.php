<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/acp/form/GroupEditForm.class.php');

/**
 * ACP: allow to add django projects
 * 
 * @author	Torben Brodt
 * @package	de.easy-coding.wcf.projects.django
 */
class GroupProjectDjangoListener implements EventListener {
	protected $projectDjango = 0;
	
	// filled during validation
	protected $projectDjangoOld = 0;
	
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		switch ($eventName) {
		case 'readFormParameters':
			if (isset($_POST['project'])) $this->project = 1;
			if (isset($_POST['projectDjango'])) $this->projectDjango = 1;
			break;
		
		
		case 'validate':
			// get old values
			$sql = "SELECT 	*
				FROM 	wcf".WCF_N."_group 
				WHERE 	groupID = {$eventObj->group->groupID}";
			$old = WCF::getDB()->getFirstRow($sql);
			$this->projectDjangoOld = $old['projectDjango'];
			
			// remove django
			if($this->projectDjangoOld && !$this->projectDjango) {
				throw new UserInputException('cannot remove django');
			}
			// move django
			else if($this->projectDjangoOld && $this->projectDjango && $this->projectShortNameOld != $this->projectShortName) {
				throw new UserInputException('cannot move django');
			}
			break;
		
		
		case 'save':
			$eventObj->additionalFields['projectDjango'] = $this->projectDjango;
			break;
		
		
		case 'saved':
			// create new django environment
			if($this->projectDjango && !$this->projectDjangoOld) {
				system("ssh 192.168.0.102 project_addDjango {$this->projectShortName}");
			}
			break;
		
		
		case 'assignVariables':
			if (!count($_POST) && $eventObj instanceof GroupEditForm) {
				$this->projectDjango = $eventObj->group->projectDjango;
			}
			
			WCF::getTPL()->assign(array(
				'projectDjango' => $this->projectDjango
			));
			WCF::getTPL()->append('additionalProjectOptions', WCF::getTPL()->fetch('groupAddProjectDjango'));
			break;
		}
	}
}
?>
