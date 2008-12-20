<?php
require_once(WCF_DIR.'lib/acp/page/UserSuggestPage.class.php');

/**
 * Outputs a xml document with revisions
 *
 * @author	Torben Brodt
 * @package	de.easy-coding.wcf.acp.page
 */
class ProjectAjaxPage extends UserSuggestPage {
	protected $groupID = 0, $rev = 0;
	protected $txt = false;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_GET['groupID'])) {
			$this->groupID = intval($_GET['groupID']);
		}
		
		if (isset($_GET['txt'])) {
			$this->txt = true;
		}
		
		if (isset($_GET['rev'])) {
			$this->rev = intval($_GET['rev']);
		}
	}
	
	/**
	 *
	 */
	protected function showTxt() {
		// sql query to fetch revisions
		$sql = "SELECT		COUNT(*) AS c,
					FROM_UNIXTIME(timestamp,'%Y-%m-%d') AS mygroup
			FROM		wcf".WCF_N."_projectSvn svn
			WHERE		groupID = ".$this->groupID."
			GROUP BY	mygroup; ";

		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			printf("%s %s\n", $row['mygroup'], $row['c']);
		}
	}
	
	protected function showRev() {
		// sql query to fetch revisions
		$sql = "SELECT		author,
					msg
			FROM		wcf".WCF_N."_projectSvn svn
			WHERE		groupID = ".$this->groupID."
			AND 		revision = ".$this->rev;

		$row = WCF::getDB()->getFirstRow($sql);
		echo '<div class="timeline-event-bubble-title"><a href="#" style="float:left" onclick="return timeBack(this)">&laquo;</a>'.$this->rev.'</div>';
		echo '<div class="timeline-event-bubble-body">';
		echo "<b>".$row['author']."</b><br />".$row['msg'];
		echo '</div>';
	}
	
	protected function showRevisions() {
		@header('Content-Type: text/xml; charset='.CHARSET);
		echo '<?xml version="1.0" encoding="'.CHARSET.'"?><data>';
		
		$tpl = '<event start="%s" end="%s" title="%s">%s</event>'."\n";
	
		// sql query to fetch revisions
		$sql = "SELECT		userID,
					author,
					timestamp,
					msg,
					GROUP_CONCAT(revision SEPARATOR ',') AS revisions,
					FROM_UNIXTIME(timestamp,'%Y-%m-%d') AS mygroup
			FROM		wcf".WCF_N."_projectSvn svn
			WHERE		groupID = ".$this->groupID."
			GROUP BY	mygroup
			ORDER BY	revision DESC; ";

		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$gmt = date('M d Y H:i:s', $row['timestamp']).' GMT';
			
			$revisions = ArrayUtil::toIntegerArray(explode(',', $row['revisions']));
			if(count($revisions) > 1) {
				$title =  min($revisions).'-'.max($revisions);
				$msg = '<ul>';
				foreach($revisions as $rev) {
					$msg .= '<li><a href="#" onclick="return timeRev(this);">'.$rev.'</a></li>';
				}
				$msg .= '</ul>';
				
			} else {
				$title =  $revisions[0];
				$msg = "<b>".$row['author']."</b><br />".$row['msg'];
			}
			
			printf($tpl, $gmt, $gmt, $title, str_replace(array('<','>'),array('&lt;','&gt;'),$msg));
		}
		
		echo '</data>';
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		//parent::show();

		if($this->rev > 0) {
			$this->showRev();
		} else if($this->txt)  {
			$this->showTxt();
		} else {
			$this->showRevisions();
		}		
		
		exit;
	}
}
?>
