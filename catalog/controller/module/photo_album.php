<?php
class ControllerModulePhotoAlbum extends Controller {
	public function index($setting) {
		static $module = 0;

		$this->load->model('photo/photo');
		$this->load->model('tool/image');

		$this->document->addStyle('catalog/view/javascript/bcit-carousel/slick.css');
        $this->document->addStyle('catalog/view/javascript/bcit-carousel/slick-theme.css');
		$this->document->addScript('catalog/view/javascript/bcit-carousel/slick.min.js');
        $data['heading_title'] = $setting['name'];
		$data['banners'] = array();

		$results = $this->model_photo_photo->getPhoto($setting['banner_id']);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$data['banners'][] = array(
					'title' => $result['title'],					
					'image' => $this->model_tool_image->resize($result['image'], 685, 300)
				);
			}
		} 

		$data['module'] = $module++;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/photo_album.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/photo_album.tpl', $data);
		} else {
			return $this->load->view('default/template/module/photo_album.tpl', $data);
		}
	}
}
