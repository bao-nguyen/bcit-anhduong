<?php
class ModelCatalognews extends Model {
	public function addnews($data) {
		$this->event->trigger('pre.admin.news.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "news SET date_available = '" . $this->db->escape($data['date_available']) . "', status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW()");

		$news_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "news SET image = '" . $this->db->escape($data['image']) . "' WHERE news_id = '" . (int)$news_id . "'");
		}

		foreach ($data['news_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "news_description SET news_id = '" . (int)$news_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', content = '" . $this->db->escape($value['content']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['news_store'])) {
			foreach ($data['news_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_to_store SET news_id = '" . (int)$news_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['news_download'])) {
			foreach ($data['news_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_to_download SET news_id = '" . (int)$news_id . "', download_id = '" . (int)$download_id . "'");
			}
		}
        
        if (isset($data['news_viewpdf'])) {
			foreach ($data['news_viewpdf'] as $viewpdf_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_to_viewpdf SET news_id = '" . (int)$news_id . "', viewpdf_id = '" . (int)$viewpdf_id . "'");
			}
		}

		if (isset($data['news_ncategory'])) {
			foreach ($data['news_ncategory'] as $ncategory_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_to_ncategory SET news_id = '" . (int)$news_id . "', ncategory_id = '" . (int)$ncategory_id . "'");
			}
		}

		if (isset($data['news_filter'])) {
			foreach ($data['news_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_filter SET news_id = '" . (int)$news_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		if (isset($data['news_related'])) {
			foreach ($data['news_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "news_related WHERE news_id = '" . (int)$news_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_related SET news_id = '" . (int)$news_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "news_related WHERE news_id = '" . (int)$related_id . "' AND related_id = '" . (int)$news_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_related SET news_id = '" . (int)$related_id . "', related_id = '" . (int)$news_id . "'");
			}
		}

		if (isset($data['news_layout'])) {
			foreach ($data['news_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_to_layout SET news_id = '" . (int)$news_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'news_id=" . (int)$news_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('news');

		$this->event->trigger('post.admin.news.add', $news_id);

		return $news_id;
	}

	public function editnews($news_id, $data) {
		$this->event->trigger('pre.admin.news.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "news SET date_available = '" . $this->db->escape($data['date_available']) . "', status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE news_id = '" . (int)$news_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "news SET image = '" . $this->db->escape($data['image']) . "' WHERE news_id = '" . (int)$news_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "news_description WHERE news_id = '" . (int)$news_id . "'");

		foreach ($data['news_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "news_description SET news_id = '" . (int)$news_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', content = '" . $this->db->escape($value['content']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "news_to_store WHERE news_id = '" . (int)$news_id . "'");

		if (isset($data['news_store'])) {
			foreach ($data['news_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_to_store SET news_id = '" . (int)$news_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "news_to_download WHERE news_id = '" . (int)$news_id . "'");

		if (isset($data['news_download'])) {
			foreach ($data['news_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_to_download SET news_id = '" . (int)$news_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

        $this->db->query("DELETE FROM " . DB_PREFIX . "news_to_viewpdf WHERE news_id = '" . (int)$news_id . "'");

		if (isset($data['news_viewpdf'])) {
			foreach ($data['news_viewpdf'] as $viewpdf_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_to_viewpdf SET news_id = '" . (int)$news_id . "', viewpdf_id = '" . (int)$viewpdf_id . "'");
			}
		}
        
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_to_ncategory WHERE news_id = '" . (int)$news_id . "'");

		if (isset($data['news_ncategory'])) {
			foreach ($data['news_ncategory'] as $ncategory_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_to_ncategory SET news_id = '" . (int)$news_id . "', ncategory_id = '" . (int)$ncategory_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "news_filter WHERE news_id = '" . (int)$news_id . "'");

		if (isset($data['news_filter'])) {
			foreach ($data['news_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_filter SET news_id = '" . (int)$news_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "news_related WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_related WHERE related_id = '" . (int)$news_id . "'");

		if (isset($data['news_related'])) {
			foreach ($data['news_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "news_related WHERE news_id = '" . (int)$news_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_related SET news_id = '" . (int)$news_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "news_related WHERE news_id = '" . (int)$related_id . "' AND related_id = '" . (int)$news_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_related SET news_id = '" . (int)$related_id . "', related_id = '" . (int)$news_id . "'");
			}
		}


		$this->db->query("DELETE FROM " . DB_PREFIX . "news_to_layout WHERE news_id = '" . (int)$news_id . "'");

		if (isset($data['news_layout'])) {
			foreach ($data['news_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_to_layout SET news_id = '" . (int)$news_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'news_id=" . (int)$news_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'news_id=" . (int)$news_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('news');

		$this->event->trigger('post.admin.news.edit', $news_id);
	}

	public function copynews($news_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "news p LEFT JOIN " . DB_PREFIX . "news_description pd ON (p.news_id = pd.news_id) WHERE p.news_id = '" . (int)$news_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		if ($query->num_rows) {
			$data = $query->row;
            
			$data['viewed'] = '0';
			$data['keyword'] = '';
			$data['status'] = '0';
            
			$data['news_description'] = $this->getnewsDescriptions($news_id);
			$data['news_filter'] = $this->getnewsFilters($news_id);
			$data['news_image'] = $this->getnewsImages($news_id);
			$data['news_related'] = $this->getnewsRelated($news_id);
			$data['news_ncategory'] = $this->getnewsCategories($news_id);
			$data['news_download'] = $this->getnewsDownloads($news_id);
            $data['news_viewpdf'] = $this->getnewsViewpdfs($news_id);
			$data['news_layout'] = $this->getnewsLayouts($news_id);
			$data['news_store'] = $this->getnewsStores($news_id);

			$this->addnews($data);
		}
	}

	public function deletenews($news_id) {
		$this->event->trigger('pre.admin.news.delete', $news_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "news WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_description WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_discount WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_filter WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_related WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_related WHERE related_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_to_ncategory WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_to_download WHERE news_id = '" . (int)$news_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "news_to_viewpdf WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_to_layout WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_to_store WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_review WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'news_id=" . (int)$news_id . "'");

		$this->cache->delete('news');

		$this->event->trigger('post.admin.news.delete', $news_id);
	}

	public function getnews($news_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'news_id=" . (int)$news_id . "') AS keyword FROM " . DB_PREFIX . "news p LEFT JOIN " . DB_PREFIX . "news_description pd ON (p.news_id = pd.news_id) WHERE p.news_id = '" . (int)$news_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getnewss($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "news p LEFT JOIN " . DB_PREFIX . "news_description pd ON (p.news_id = pd.news_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " GROUP BY p.news_id";

		$sort_data = array(
			'pd.name',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
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

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getnewssByncategoryId($ncategory_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news p LEFT JOIN " . DB_PREFIX . "news_description pd ON (p.news_id = pd.news_id) LEFT JOIN " . DB_PREFIX . "news_to_ncategory p2c ON (p.news_id = p2c.news_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.ncategory_id = '" . (int)$ncategory_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function getnewsDescriptions($news_id) {
		$news_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_description WHERE news_id = '" . (int)$news_id . "'");

		foreach ($query->rows as $result) {
			$news_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
                'content'          => $result['content'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'tag'              => $result['tag']
			);
		}

		return $news_description_data;
	}

	public function getnewsCategories($news_id) {
		$news_ncategory_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_to_ncategory WHERE news_id = '" . (int)$news_id . "'");

		foreach ($query->rows as $result) {
			$news_ncategory_data[] = $result['ncategory_id'];
		}

		return $news_ncategory_data;
	}

	public function getnewsFilters($news_id) {
		$news_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_filter WHERE news_id = '" . (int)$news_id . "'");

		foreach ($query->rows as $result) {
			$news_filter_data[] = $result['filter_id'];
		}

		return $news_filter_data;
	}

	public function getnewsImages($news_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_image WHERE news_id = '" . (int)$news_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getnewsDownloads($news_id) {
		$news_download_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_to_download WHERE news_id = '" . (int)$news_id . "'");

		foreach ($query->rows as $result) {
			$news_download_data[] = $result['download_id'];
		}

		return $news_download_data;
	}
    
    public function getnewsviewpdfs($news_id) {
		$news_viewpdf_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_to_viewpdf WHERE news_id = '" . (int)$news_id . "'");

		foreach ($query->rows as $result) {
			$news_viewpdf_data[] = $result['viewpdf_id'];
		}

		return $news_viewpdf_data;
	}

	public function getnewsStores($news_id) {
		$news_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_to_store WHERE news_id = '" . (int)$news_id . "'");

		foreach ($query->rows as $result) {
			$news_store_data[] = $result['store_id'];
		}

		return $news_store_data;
	}

	public function getnewsLayouts($news_id) {
		$news_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_to_layout WHERE news_id = '" . (int)$news_id . "'");

		foreach ($query->rows as $result) {
			$news_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $news_layout_data;
	}

	public function getnewsRelated($news_id) {
		$news_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_related WHERE news_id = '" . (int)$news_id . "'");

		foreach ($query->rows as $result) {
			$news_related_data[] = $result['related_id'];
		}

		return $news_related_data;
	}

	public function getTotalnewss($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.news_id) AS total FROM " . DB_PREFIX . "news p LEFT JOIN " . DB_PREFIX . "news_description pd ON (p.news_id = pd.news_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalnewssByDownloadId($download_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "news_to_download WHERE download_id = '" . (int)$download_id . "'");

		return $query->row['total'];
	}
    
    public function getTotalnewssByViewpdfId($viewpdf_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "news_to_viewpdf WHERE viewpdf_id = '" . (int)$viewpdf_id . "'");

		return $query->row['total'];
	}

	public function getTotalnewssByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "news_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
}
