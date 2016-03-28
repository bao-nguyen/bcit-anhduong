<?php
class ControllerModulemenu extends Controller {
	public function index() {
		$this->load->language('module/menu');

        // Menu
		$this->load->model('catalog/menu');
		
        $data['text_menu'] = $this->language->get('text_menu');
		$data['menus'] = array();

		$menus = $this->model_catalog_menu->getmenus(0);

		foreach ($menus as $menu) {
		
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_menu->getmenus($menu['menu_id']);

				foreach ($children as $child) {
					$filter_data = array(
						'filter_menu_id'  => $child['menu_id'],
						'filter_sub_menu' => true
					);

					$children_data[] = array(
						'name'  => $child['name'],
						'href'  => ($child['link'])
					);
				}

				// Level 1
				$data['menus'][] = array(
					'name'     => $menu['name'],
					'children' => $children_data,
					'column'   => $menu['column'] ? $menu['column'] : 1,
					'href'     => ($menu['link'])
				);
			
		}
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/menu.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/menu.tpl', $data);
		} else {
			return $this->load->view('default/template/module/menu.tpl', $data);
		}
	}
}