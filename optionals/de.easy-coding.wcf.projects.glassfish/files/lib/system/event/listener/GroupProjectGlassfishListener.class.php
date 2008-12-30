<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/acp/form/GroupEditForm.class.php');

/**
 * ACP: allow to add glassfish projects
 * 
 * @author	Torben Brodt
 * @package	de.easy-coding.wcf.projects.glassfish
 */
class GroupProjectGlassfishListener implements EventListener {
	protected $projectGlassfish = 0;
	
	// filled during validation
	protected $projectGlassfishOld = 0;
	
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		switch ($eventName) {
		case 'readFormParameters':
			if (isset($_POST['project'])) $this->project = 1;
			if (isset($_POST['projectGlassfish'])) $this->projectGlassfish = 1;
			break;
		
		
		case 'validate':
			if($eventObj->group) {
				// get old values
				$sql = "SELECT 	*
					FROM 	wcf".WCF_N."_group 
					WHERE 	groupID = {$eventObj->group->groupID}";
				$old = WCF::getDB()->getFirstRow($sql);
				$this->projectGlassfishOld = $old['projectGlassfish'];
			}
			
			// remove glassfish
			if($this->projectGlassfishOld && !$this->projectGlassfish) {
				throw new UserInputException('cannot remove glassfish');
			}
			// move glassfish
			else if($this->projectGlassfishOld && $this->projectGlassfish && $this->projectShortNameOld != $this->projectShortName) {
				throw new UserInputException('cannot move glassfish');
			}
			break;
		
		
		case 'save':
			$eventObj->additionalFields['projectGlassfish'] = $this->projectGlassfish;
			break;
		
		
		case 'saved':
			// create new glassfish environment
			if($this->projectGlassfish && !$this->projectGlassfishOld) {
				system("ssh 192.168.0.102 project_addGlassfish {$this->projectShortName}");
			}
			break;
		
		
		case 'assignVariables':
			if (!count($_POST) && $eventObj instanceof GroupEditForm) {
				$this->projectGlassfish = $eventObj->group->projectGlassfish;
			}
			
			WCF::getTPL()->assign(array(
				'projectGlassfish' => $this->projectGlassfish
			));
			WCF::getTPL()->append('additionalProjectOptions', WCF::getTPL()->fetch('groupAddProjectGlassfish'));
			break;
		}
	}
}
?>
