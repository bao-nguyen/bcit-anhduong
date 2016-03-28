<?php
class ControllerModuleLatestByCategory extends Controller {
	public function index($setting) {
        static $module = 0;
		$this->load->language('module/latest_by_category');
		
		$this->load->model('catalog/ncategory');
		$category_info = $this->model_catalog_ncategory->getnCategory($setting['ncategory_id']);
		if($category_info) {  
			$data['heading_title'] = $category_info['name'];
		} else {
			$data['heading_title'] = $this->language->get('heading_title');
		}
        
        $this->document->addStyle('catalog/view/javascript/bcit-carousel/slick.css');
        $this->document->addStyle('catalog/view/javascript/bcit-carousel/slick-theme.css');
		$this->document->addScript('catalog/view/javascript/bcit-carousel/slick.min.js');
                		

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/news');

		$this->load->model('tool/image');

		$data['newss'] = array();

		$filter_data = array(
			'sort' 				 	=> 'p.date_added',
			'filter_ncategory_id'	=> (int)$setting['ncategory_id'],
			'order' 				=> 'DESC',
			'start' 				=> 0,
			'limit' 				=> $setting['limit']
		);
  
        
        
		$results = $this->model_catalog_news->getnewss($filter_data);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}

				$data['newss'][] = array(
					'news_id'  => $result['news_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_news_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $rating,
					'href'        => $this->url->link('news/news', 'news_id=' . $result['news_id']),
				);
			}		
            
            $data['module'] = $module++;
            
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/latest_by_category.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/latest_by_category.tpl', $data);
			} else {
				return $this->load->view('default/template/module/latest_by_category.tpl', $data);
			}
		}
	}
}