<?php
class ControllerModuleNewsLatest extends Controller {
	public function index($setting) {
		$this->load->language('module/news_latest');

		$data['heading_title'] = $setting['name'];

		$this->load->model('catalog/news');

		$this->load->model('tool/image');

		$data['newss'] = array();

		$filter_data = array(
			'sort'  => 'p.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => $setting['limit']
		);

		$results = $this->model_catalog_news->getnewss($filter_data);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
					//$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
                    $image = $this->model_tool_image->resize($result['image'], 50, 50);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', 50, 50);
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
					'href'        => $this->url->link('news/news', 'news_id=' . $result['news_id'])
				);
			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/news_latest.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/news_latest.tpl', $data);
			} else {
				return $this->load->view('default/template/module/news_latest.tpl', $data);
			}
		}
	}
}
