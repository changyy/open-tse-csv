<?php

class MY_Controller extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->config('stock');
		$this->fetch_cache_dir = $this->config->item('storage_dir');
	}

	function _get_cache_file($tag, $suffix, $dir = NULL) {
		$target_dir = $this->fetch_cache_dir;
		if (!empty($dir)) {
			$target_dir = $target_dir . DIRECTORY_SEPARATOR . basename($dir);
			if (!is_dir($target_dir))
				mkdir($target_dir);
			if (!is_dir($target_dir))
				$target_dir = $this->fetch_cache_dir;
		}
		return $target_dir . DIRECTORY_SEPARATOR . basename($tag) . "."  . basename($suffix);
	}

	function _fetch_resource($url, $params = array()) {
		$output = array();
		$output['url'] = $url;
		$output['params'] = $params;
		$output['target_url'] = isset($params['get']) && is_array($params['get']) && count($params['get']) > 0 ? $url.'?'.http_build_query($params['get']) : $url;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $output['target_url']);
		if (isset($params['req_header']) && is_array($params['req_header']) && count($params['req_header']) > 0)
			curl_setopt($ch, CURLOPT_HTTPHEADER, $params['req_header']);
		if (isset($params['user_agent']))
			curl_setopt($ch, CURLOPT_USERAGENT, $params['user_agent']);
		if (isset($params['post']) && is_array($params['post'])) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params['post']));
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$output['ret'] = curl_exec($ch);
		$output['code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);;
		curl_close($ch);
		return $output;
	}

	function _json_output($output) {
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}
}
