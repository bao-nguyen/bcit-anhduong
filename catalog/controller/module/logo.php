<?php
class ControllerModuleLogo extends Controller {
	public function index() {
		$this->load->language('module/logo');		
  
        if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = HTTP_SERVER;
		}

        $data['name'] = $this->config->get('config_name');
        $data['url'] = $server;
        
		
        if (is_file(DIR_IMAGE . $this->config->get('config_image'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_image');
		} else {
			$data['logo'] = '';
		}
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/logo.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/logo.tpl', $data);
		} else {
			return $this->load->view('default/template/module/logo.tpl', $data);
		}
	}
}