<?php
class ControllerInformationForm extends Controller {
	public function index() {
		$this->load->language('information/form');

		$this->load->model('catalog/bcit_download');
        
        $this->document->setTitle($this->language->get('heading_title'));
                
        
            
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/form')
		);

		$data['heading_title'] = $this->language->get('heading_title');                       
        
        $data['forms'] = array();
        
		$results = $this->model_catalog_bcit_download->getForms();
        
		if ($results) {
			foreach ($results as $result) {
    			if (file_exists(DIR_DOWNLOAD . $result['filename'])) {
    				$size = filesize(DIR_DOWNLOAD . $result['filename']);
    		
    				$i = 0;
    		
    				$suffix = array(
    						'B',
    						'KB',
    						'MB',
    						'GB',
    						'TB',
    						'PB',
    						'EB',
    						'ZB',
    						'YB'
    				);
    		
    				while (($size / 1024) > 1) {
    					$size = $size / 1024;
    					$i++;
    				}
    		
    				$data['forms'][] = array(
    						
    						'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
    						'name'       => $result['name'],
    						'test1'		 => $result['filename'],
    						'filename'		 => $result['mask'],
    						'size'       => round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i],
    						'href'       => $this->url->link('information/form/download', 'form_id=' . $result['form_id'], 'SSL')
    				);
    			}
    		}		
		}                
        
        $data['text_empty'] = $this->language->get('text_empty');

		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_name'] = $this->language->get('column_name');
        $data['column_filename'] = $this->language->get('column_filename');
		$data['column_size'] = $this->language->get('column_size');
		$data['column_date_added'] = $this->language->get('column_date_added');

		$data['button_download'] = $this->language->get('button_download');
		$data['button_continue'] = $this->language->get('button_continue');
        
        $data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');		

						$data['bcit_header'] = $this->load->controller('common/bcit_header');
						$data['bcit_logo'] = $this->load->controller('common/bcit_logo');
						$data['bcit_banner'] = $this->load->controller('common/bcit_banner');
						$data['bcit_menu'] = $this->load->controller('common/bcit_menu');
						$data['bcit_footer'] = $this->load->controller('common/bcit_footer');
						
            
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/form.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/form.tpl', $data));
		} else {
				$this->response->setOutput($this->load->view('default/template/information/form.tpl', $data));
		}
    }
    
    public function download() {
		
		$this->load->model('catalog/bcit_download');

		if (isset($this->request->get['form_id'])) {
			$form_id = $this->request->get['form_id'];
		} else {
			$form_id = 0;
		}

		$form_info = $this->model_catalog_bcit_download->getForm($form_id);

		if ($form_info) {
			$file = DIR_DOWNLOAD . $form_info[0]['filename'];
			$mask = basename($form_info[0]['mask']);

			if (!headers_sent()) {
				if (file_exists($file)) {
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($file));

					if (ob_get_level()) {
						ob_end_clean();
					}

					readfile($file, 'rb');

					exit();
				} else {
					exit('Error: Could not find file ' . $file . '!');
				}
			} else {
				exit('Error: Headers already sent out!');
			}
		} else {
			$this->response->redirect($this->url->link('account/download', '', 'SSL'));
		}
	}
}
