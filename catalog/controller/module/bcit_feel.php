<?php
class ControllerModuleBcitFeel extends Controller {
	public function index($setting) {
        static $module = 0;
				
	    $data['heading_title'] = $setting['name'];
		$this->load->model('catalog/feel');
		$this->load->model('tool/image');

		$data['feels'] = array();	
        
        
		$results = $this->model_catalog_feel->getFeels($setting['limit']);

        $data['test'] = $results;
		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], 50, 50);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', 50, 50);
				}				

				$data['feels'][] = array(
					'feel_id'  => $result['feel_id'],
					'thumb'       => $image,
					'name'        => $result['name'],					
					'company'        => $result['company'],
                    'feel'        => $result['feel'],
				);
			}		
            
            $data['module'] = $module++;
            
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/bcit_feel.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/bcit_feel.tpl', $data);
			} else {
				return $this->load->view('default/template/module/bcit_feel.tpl', $data);
			}
		}
	}
}