<?php
class ControllerModuleNewsFeatured extends Controller {
	public function index($setting) {
	    static $module = 0;
		$this->load->language('module/news_featured');

        $this->document->addStyle('catalog/view/javascript/bcit-carousel/slick.css');
        $this->document->addStyle('catalog/view/javascript/bcit-carousel/slick-theme.css');
		$this->document->addScript('catalog/view/javascript/bcit-carousel/slick.min.js');
        
		//$data['heading_title'] = $this->language->get('heading_title');
        $data['heading_title'] = $setting['name'];
        $data['image'] = 'image/' . $setting['image'];
		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/news');

		$this->load->model('tool/image');

		$data['newss'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		$newss = array_slice($setting['news'], 0, (int)$setting['limit']);

		foreach ($newss as $news_id) {
			$news_info = $this->model_catalog_news->getnews($news_id);

			if ($news_info) {
				if ($news_info['image']) {
					$image = $this->model_tool_image->resize($news_info['image'], 253 , 253 );
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', 253, 253);
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($news_info['price'], $news_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$news_info['special']) {
					$special = $this->currency->format($this->tax->calculate($news_info['special'], $news_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$news_info['special'] ? $news_info['special'] : $news_info['price']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $news_info['rating'];
				} else {
					$rating = false;
				}

				$data['newss'][] = array(
					'news_id'  => $news_info['news_id'],
					'thumb'       => $image,
					'name'        => $news_info['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($news_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_news_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $rating,
					'href'        => $this->url->link('news/news', 'news_id=' . $news_info['news_id'])
				);
			}
		}
        
        $data['module'] = $module++;        
        
		if ($data['newss']) {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/'.$setting['layout'].'.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/'.$setting['layout'].'.tpl', $data);
			} else {
				return $this->load->view('default/template/module/news_featured.tpl', $data);
			}
		}
	}
}