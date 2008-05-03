<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');

/**
 * Shows details of one project...
 * 
 * @author	Torben Brodt
 * @package	de.easy-coding.wcf.page
 */
class ProjectRevisionAjaxPage extends AbstractPage {
	protected $groupID=0;
	protected $start;
	protected $end;
	protected $revisions = array();

	/**
         * @see Page::readParameters()
         */
        public function readParameters() {
		parent::readParameters();

		if(isset($_GET['groupID'])) $this->groupID = intval($_GET['groupID']);
		if(isset($_REQUEST['start'])) { 
			preg_match('/^(.+\d{4}) \d{2}:\d{2}:\d{2}/', $_REQUEST['start'], $res);
			$this->start =  strtotime($res[1]);
		}
		if(isset($_REQUEST['end'])) {
			preg_match('/^(.+\d{4}) \d{2}:\d{2}:\d{2}/', $_REQUEST['end'], $res);
			$this->end =  strtotime($res[1]);
		}
        }
        
        /**
         * @see Page::readData()
         */
        public function readData() {
		parent::readData();

		// sql query to fetch revisions
		$sql = "SELECT		revision,
					userID,
					author,
					timestamp,
					msg
			FROM		wcf".WCF_N."_projectSvn svn
			WHERE		groupID = ".$this->groupID."
			AND		timestamp
			BETWEEN 	".$this->start."
			AND		".$this->end."
			ORDER BY	revision DESC; ";

		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->revisions[] = $row;
		}
        }

	/**
	 * @see Page::show()
	 */
	public function show() {
		parent::show();

		//header('Content-type: text/xml');
		$tpl = '<rev id="%d" userID="%d" author="%s" timestamp="%s"><![CDATA[%s]]></rev>';
		echo '<?xml version="1.0" encoding="'.CHARSET.'"?><data>';
		foreach($this->revisions as $rev) {
			printf($tpl, $rev['revision'], $rev['userID'], $rev['author'], date('d.m.y H:i',$rev['timestamp']), $rev['msg']);
		}
		echo '</data>';
		exit;
	}
}
?>
