<?php
// WCF include
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

// my includes
require_once(WCF_DIR.'lib/util/xmlrpc/xmlrpc.inc');

/**
 * cache for all trac projects
 * 
 * @author	Torben Brodt
 * @package	de.easy-coding.wcf.projects
 */
class CacheBuilderProjectsTrac implements CacheBuilder {

	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		$store = array();

		$sql = "SELECT 	groupID,
				projectShortName 
			FROM 	wcf".WCF_N."_group
			WHERE	projectIntern = 1
			AND	projectTrac = 1";
		
		// query
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$tmp = $this->query($row['projectShortName']);
			if(!empty($tmp)) $store[$row['groupID']] = $tmp;
		}

		return $store;
	}
	
	/**
	 *
	 * @param projectShortName
	 */
	private function query($projectShortName) {
		$client = new xmlrpc_client('http://svncron:Yu3nuWaeDa3Ov7ha@trac.easy-coding.de/trac/'.$projectShortName.'/login/xmlrpc');

		$store = array();

		$calls = array();
		$calls['closed'] = new xmlrpcmsg('ticket.query',array(new xmlrpcval("status=closed", "string")));
		$calls['notclosed'] = new xmlrpcmsg('ticket.query',array(new xmlrpcval("status!=closed", "string")));
		$calls['milestones'] = new xmlrpcmsg('ticket.milestone.getAll');
		$calls['pages'] = new xmlrpcmsg('wiki.getAllPages');

		$idx = array_keys($calls);

		$rows = &$client->multicall($calls);
		foreach($rows as $key => $val) {
			if($val->faultCode()) continue;

			$val = php_xmlrpc_decode($val->value());

			switch($idx[$key]) {
				case 'closed':
				case 'notclosed':
					$val = count($val);
				break;
				case 'pages':
					$val = count(array_filter($val, create_function('$a', 'return !preg_match("/^(Wiki|Trac)/", $a);')));
				break;
			}

			$store[$idx[$key]] = $val;
		}
		return $store;
	}
}
?>
