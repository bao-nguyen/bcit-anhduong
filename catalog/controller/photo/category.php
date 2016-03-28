<?php
class ControllerPhotoCategory extends Controller {
	public function index() {
		$this->load->language('photo/category');
		$this->load->model('photo/category');


		$this->document->setTitle($this->language->get('heading_title'));
                
        
            
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('photo/category')
		);

		$data['heading_title'] = $this->language->get('heading_title');                       
        
        $data['albums'] = array();
        
		$results = $this->model_photo_category->getAlbums();
        
		if ($results) {
			foreach ($results as $result) {
    			$data['albums'][] = array(
    						
    						'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                            'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
    						'name'       => $result['name'],    						
    						'href'       => $this->url->link('photo/photo', 'album_id=' . $result['photo_id'], 'SSL')
    				);
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

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/photo/category.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/photo/category.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/photo/category.tpl', $data));
			}
		
	}
}
