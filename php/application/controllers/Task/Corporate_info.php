<?php
class Corporate_Info extends MY_Controller {
	public function index() {
	}

	public function daily($date = NULL, $nocache = NULL) {
		$output = array( 'status' => false, 'cache' => array(), 'data' => array());
		if (empty($date))
			$date = sprintf("%s/%s", (int)date("Y") - 1911, date("m/d") );
		else {
			$date = str_replace("-","/", $date);
			if (count(($handle = explode("/", $date))) == 3 && (int)$handle[0] > 1000) {
				$handle[0] = (int)$handle[0] - 1911;
				$date = implode("/", $handle);
			}
		}

		$suffix = str_replace("/", "-", $date);
		if (count(($handle = explode("/", $date))) == 3 && (int)$handle[0] < 1000) {
			$handle[0] = (int)$handle[0] + 1911;
			$suffix = implode("-", $handle);
		}

		foreach( array( 
				'dealers' => 'http://www.twse.com.tw/ch/trading/fund/TWT43U/TWT43U.php',
				'foreign' => 'http://www.tse.com.tw/ch/trading/fund/TWT38U/TWT38U.php',
				'investment' => 'http://www.tse.com.tw/ch/trading/fund/TWT44U/TWT44U.php',
			) as $job_name => $url ) {

			$storage_file = $this->_get_cache_file(__FUNCTION__.'-'.$job_name, $suffix, __CLASS__);

			if (empty($nocache) && file_exists($storage_file) && filesize($storage_file) > 0) {
				$output['data'][basename($storage_file)] = file_get_contents($storage_file);
				array_push($output['cache'], basename($storage_file));
				continue;
			}
			$ret = $this->_fetch_resource($url, array(
					'post' => array(
						'qdate' => $date,
						'download' => 'csv',
						'sorting' => 'by_issue',
					)
				)
			);
			if (isset($ret['ret']) && strlen($ret['ret']) > 0) {
				$ret['ret'] = mb_convert_encoding($ret['ret'], 'UTF-8', 'Big5,UTF-8,AUTO');
				if (strlen($ret['ret']) > 0) {
					file_put_contents($storage_file, $ret['ret']);
				}
				$output['status'] = true;
				$output['data'][basename($storage_file)] = $ret['ret'];
			}
		}
		$this->_json_output($output);
	}
}
