<?php
class ModelCatalognews extends Model {
	public function updateViewed($news_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "news SET viewed = (viewed + 1) WHERE news_id = '" . (int)$news_id . "'");
	}

	public function getnews($news_id) {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "news_discount pd2 WHERE pd2.news_id = p.news_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "news_special ps WHERE ps.news_id = p.news_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "news_reward pr WHERE pr.news_id = p.news_id AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "news_review r1 WHERE r1.news_id = p.news_id AND r1.status = '1' GROUP BY r1.news_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "news_review r2 WHERE r2.news_id = p.news_id AND r2.status = '1' GROUP BY r2.news_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "news p LEFT JOIN " . DB_PREFIX . "news_description pd ON (p.news_id = pd.news_id) LEFT JOIN " . DB_PREFIX . "news_to_store p2s ON (p.news_id = p2s.news_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.news_id = '" . (int)$news_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return array(
				'news_id'       => $query->row['news_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
                'content'          => $query->row['content'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed']
			);
		} else {
			return false;
		}
	}

	public function getnewss($data = array()) {
		$sql = "SELECT p.news_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "news_review r1 WHERE r1.news_id = p.news_id AND r1.status = '1' GROUP BY r1.news_id) AS rating, (SELECT price FROM " . DB_PREFIX . "news_discount pd2 WHERE pd2.news_id = p.news_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "news_special ps WHERE ps.news_id = p.news_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";

		if (!empty($data['filter_ncategory_id'])) {
			if (!empty($data['filter_sub_ncategory'])) {
				$sql .= " FROM " . DB_PREFIX . "ncategory_path cp LEFT JOIN " . DB_PREFIX . "news_to_ncategory p2c ON (cp.ncategory_id = p2c.ncategory_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "news_to_ncategory p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "news_filter pf ON (p2c.news_id = pf.news_id) LEFT JOIN " . DB_PREFIX . "news p ON (pf.news_id = p.news_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "news p ON (p2c.news_id = p.news_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "news p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "news_description pd ON (p.news_id = pd.news_id) LEFT JOIN " . DB_PREFIX . "news_to_store p2s ON (p.news_id = p2s.news_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_ncategory_id'])) {
			if (!empty($data['filter_sub_ncategory'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_ncategory_id'] . "'";
			} else {
				$sql .= " AND p2c.ncategory_id = '" . (int)$data['filter_ncategory_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.content LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$sql .= " GROUP BY p.news_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$news_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$news_data[$result['news_id']] = $this->getnews($result['news_id']);
		}

		return $news_data;
	}

	public function getnewsSpecials($data = array()) {
		$sql = "SELECT DISTINCT ps.news_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "news_review r1 WHERE r1.news_id = ps.news_id AND r1.status = '1' GROUP BY r1.news_id) AS rating FROM " . DB_PREFIX . "news_special ps LEFT JOIN " . DB_PREFIX . "news p ON (ps.news_id = p.news_id) LEFT JOIN " . DB_PREFIX . "news_description pd ON (p.news_id = pd.news_id) LEFT JOIN " . DB_PREFIX . "news_to_store p2s ON (p.news_id = p2s.news_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.news_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$news_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$news_data[$result['news_id']] = $this->getnews($result['news_id']);
		}

		return $news_data;
	}

	public function getLatestnewss($limit) {
		$news_data = $this->cache->get('news.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$news_data) {
			$query = $this->db->query("SELECT p.news_id FROM " . DB_PREFIX . "news p LEFT JOIN " . DB_PREFIX . "news_to_store p2s ON (p.news_id = p2s.news_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$news_data[$result['news_id']] = $this->getnews($result['news_id']);
			}

			$this->cache->set('news.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $news_data);
		}

		return $news_data;
	}

	public function getPopularnewss($limit) {
		$news_data = array();

		$query = $this->db->query("SELECT p.news_id FROM " . DB_PREFIX . "news p LEFT JOIN " . DB_PREFIX . "news_to_store p2s ON (p.news_id = p2s.news_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed DESC, p.date_added DESC LIMIT " . (int)$limit);

		foreach ($query->rows as $result) {
			$news_data[$result['news_id']] = $this->getnews($result['news_id']);
		}

		return $news_data;
	}

	public function getBestSellernewss($limit) {
		$news_data = $this->cache->get('news.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$news_data) {
			$news_data = array();

			$query = $this->db->query("SELECT op.news_id, SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_news op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "news` p ON (op.news_id = p.news_id) LEFT JOIN " . DB_PREFIX . "news_to_store p2s ON (p.news_id = p2s.news_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.news_id ORDER BY total DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$news_data[$result['news_id']] = $this->getnews($result['news_id']);
			}

			$this->cache->set('news.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $news_data);
		}

		return $news_data;
	}

	public function getnewsAttributes($news_id) {
		$news_attribute_group_data = array();

		$news_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "news_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.news_id = '" . (int)$news_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

		foreach ($news_attribute_group_query->rows as $news_attribute_group) {
			$news_attribute_data = array();

			$news_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "news_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.news_id = '" . (int)$news_id . "' AND a.attribute_group_id = '" . (int)$news_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");

			foreach ($news_attribute_query->rows as $news_attribute) {
				$news_attribute_data[] = array(
					'attribute_id' => $news_attribute['attribute_id'],
					'name'         => $news_attribute['name'],
					'text'         => $news_attribute['text']
				);
			}

			$news_attribute_group_data[] = array(
				'attribute_group_id' => $news_attribute_group['attribute_group_id'],
				'name'               => $news_attribute_group['name'],
				'attribute'          => $news_attribute_data
			);
		}

		return $news_attribute_group_data;
	}

	public function getnewsOptions($news_id) {
		$news_option_data = array();

		$news_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.news_id = '" . (int)$news_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

		foreach ($news_option_query->rows as $news_option) {
			$news_option_value_data = array();

			$news_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.news_id = '" . (int)$news_id . "' AND pov.news_option_id = '" . (int)$news_option['news_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

			foreach ($news_option_value_query->rows as $news_option_value) {
				$news_option_value_data[] = array(
					'news_option_value_id' => $news_option_value['news_option_value_id'],
					'option_value_id'         => $news_option_value['option_value_id'],
					'name'                    => $news_option_value['name'],
					'image'                   => $news_option_value['image'],
					'quantity'                => $news_option_value['quantity'],
					'subtract'                => $news_option_value['subtract'],
					'price'                   => $news_option_value['price'],
					'price_prefix'            => $news_option_value['price_prefix'],
					'weight'                  => $news_option_value['weight'],
					'weight_prefix'           => $news_option_value['weight_prefix']
				);
			}

			$news_option_data[] = array(
				'news_option_id'    => $news_option['news_option_id'],
				'news_option_value' => $news_option_value_data,
				'option_id'            => $news_option['option_id'],
				'name'                 => $news_option['name'],
				'type'                 => $news_option['type'],
				'value'                => $news_option['value'],
				'required'             => $news_option['required']
			);
		}

		return $news_option_data;
	}

	public function getnewsDiscounts($news_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_discount WHERE news_id = '" . (int)$news_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

		return $query->rows;
	}

	public function getnewsImages($news_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_image WHERE news_id = '" . (int)$news_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getnewsRelated($news_id) {
		$news_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_related pr LEFT JOIN " . DB_PREFIX . "news p ON (pr.related_id = p.news_id) LEFT JOIN " . DB_PREFIX . "news_to_store p2s ON (p.news_id = p2s.news_id) WHERE pr.news_id = '" . (int)$news_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		foreach ($query->rows as $result) {
			$news_data[$result['related_id']] = $this->getnews($result['related_id']);
		}

		return $news_data;
	}

	public function getnewsLayoutId($news_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_to_layout WHERE news_id = '" . (int)$news_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getCategories($news_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_to_ncategory WHERE news_id = '" . (int)$news_id . "'");

		return $query->rows;
	}

	public function getTotalnewss($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.news_id) AS total";

		if (!empty($data['filter_ncategory_id'])) {
			if (!empty($data['filter_sub_ncategory'])) {
				$sql .= " FROM " . DB_PREFIX . "ncategory_path cp LEFT JOIN " . DB_PREFIX . "news_to_ncategory p2c ON (cp.ncategory_id = p2c.ncategory_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "news_to_ncategory p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "news_filter pf ON (p2c.news_id = pf.news_id) LEFT JOIN " . DB_PREFIX . "news p ON (pf.news_id = p.news_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "news p ON (p2c.news_id = p.news_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "news p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "news_description pd ON (p.news_id = pd.news_id) LEFT JOIN " . DB_PREFIX . "news_to_store p2s ON (p.news_id = p2s.news_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_ncategory_id'])) {
			if (!empty($data['filter_sub_ncategory'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_ncategory_id'] . "'";
			} else {
				$sql .= " AND p2c.ncategory_id = '" . (int)$data['filter_ncategory_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.content LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getProfile($news_id, $recurring_id) {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "recurring` `p` JOIN `" . DB_PREFIX . "news_recurring` `pp` ON `pp`.`recurring_id` = `p`.`recurring_id` AND `pp`.`news_id` = " . (int)$news_id . " WHERE `pp`.`recurring_id` = " . (int)$recurring_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int)$this->config->get('config_customer_group_id'))->row;
	}

	public function getProfiles($news_id) {
		return $this->db->query("SELECT `pd`.* FROM `" . DB_PREFIX . "news_recurring` `pp` JOIN `" . DB_PREFIX . "recurring_description` `pd` ON `pd`.`language_id` = " . (int)$this->config->get('config_language_id') . " AND `pd`.`recurring_id` = `pp`.`recurring_id` JOIN `" . DB_PREFIX . "recurring` `p` ON `p`.`recurring_id` = `pd`.`recurring_id` WHERE `news_id` = " . (int)$news_id . " AND `status` = 1 AND `customer_group_id` = " . (int)$this->config->get('config_customer_group_id') . " ORDER BY `sort_order` ASC")->rows;
	}

	public function getTotalnewsSpecials() {
		$query = $this->db->query("SELECT COUNT(DISTINCT ps.news_id) AS total FROM " . DB_PREFIX . "news_special ps LEFT JOIN " . DB_PREFIX . "news p ON (ps.news_id = p.news_id) LEFT JOIN " . DB_PREFIX . "news_to_store p2s ON (p.news_id = p2s.news_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");

		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}
}
