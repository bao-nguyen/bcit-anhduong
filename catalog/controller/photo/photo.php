<?php
class ControllerPhotoPhoto extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('photo/photo');
        
        $this->document->addStyle('catalog/view/javascript/bcit-carousel/thuvienanh.css');
        $this->document->addStyle('catalog/view/javascript/bcit-carousel/slick.css');
        $this->document->addStyle('catalog/view/javascript/bcit-carousel/slick-theme.css');
		$this->document->addScript('catalog/view/javascript/bcit-carousel/slick.min.js');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
        
        $data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('photo/category')
		);
        
        $this->load->model('photo/category');
        $this->load->model('photo/photo');
        
        if (isset($this->request->get['album_id'])) {
			$album_info = $this->model_photo_category->getAlbum($this->request->get['album_id']);
		}
        
        
        if ($album_info) {
            
            $data['breadcrumbs'][] = array(
            'text' => $album_info[0]['name'],
            'href' => $this->url->link('photo/photo', '&album_id=' . $this->request->get['album_id'])
				);

			$data['heading_title'] = $album_info[0]['name'];
            if (isset($this->request->get['album_id'])) {
                $photo_images = $this->model_photo_photo->getAlbumImages($this->request->get['album_id']);
            }else {
                $photo_images = '';  
            }  
            $data['photo_images'] = array();
            $this->load->model('tool/image');
            foreach ($photo_images as $photo_image) {
                if (is_file(DIR_IMAGE . $photo_image['image'])) {
				    $image = DIR_IMAGE .$photo_image['image'];
				    $thumb = $photo_image['image'];
			     } else {
				    $image = '';
				    $thumb = 'no_image.png';
			     }
                
    			$data['photo_images'][] = array(
    				'photo_image_description' => $photo_image['photo_image_description'],				
    				'image'                    => $image,
    				'thumb'                    => $this->model_tool_image->resize($thumb, 100, 100),
    				'sort_order'               => $photo_image['sort_order']
    			);
            }
        
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
            
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/photo/photo.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/photo/photo.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/photo/photo.tpl', $data));
			}
        } else {
		

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('photo/photo', $url . '&photo_id=' . $news_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

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
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
        
	}

	
}
