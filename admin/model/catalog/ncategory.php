<?php
class ModelCatalogncategory extends Model {
	public function addncategory($data) {
		$this->event->trigger('pre.admin.ncategory.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "ncategory SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");

		$ncategory_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "ncategory SET image = '" . $this->db->escape($data['image']) . "' WHERE ncategory_id = '" . (int)$ncategory_id . "'");
		}

		foreach ($data['ncategory_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "ncategory_description SET ncategory_id = '" . (int)$ncategory_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$level = 0;

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ncategory_path` WHERE ncategory_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");

		foreach ($query->rows as $result) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "ncategory_path` SET `ncategory_id` = '" . (int)$ncategory_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

			$level++;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "ncategory_path` SET `ncategory_id` = '" . (int)$ncategory_id . "', `path_id` = '" . (int)$ncategory_id . "', `level` = '" . (int)$level . "'");

		if (isset($data['ncategory_filter'])) {
			foreach ($data['ncategory_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ncategory_filter SET ncategory_id = '" . (int)$ncategory_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		if (isset($data['ncategory_store'])) {
			foreach ($data['ncategory_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ncategory_to_store SET ncategory_id = '" . (int)$ncategory_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		// Set which layout to use with this ncategory
		if (isset($data['ncategory_layout'])) {
			foreach ($data['ncategory_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ncategory_to_layout SET ncategory_id = '" . (int)$ncategory_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'ncategory_id=" . (int)$ncategory_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('ncategory');

		$this->event->trigger('post.admin.ncategory.add', $ncategory_id);

		return $ncategory_id;
	}

	public function editncategory($ncategory_id, $data) {
		$this->event->trigger('pre.admin.ncategory.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "ncategory SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE ncategory_id = '" . (int)$ncategory_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "ncategory SET image = '" . $this->db->escape($data['image']) . "' WHERE ncategory_id = '" . (int)$ncategory_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "ncategory_description WHERE ncategory_id = '" . (int)$ncategory_id . "'");

		foreach ($data['ncategory_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "ncategory_description SET ncategory_id = '" . (int)$ncategory_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ncategory_path` WHERE path_id = '" . (int)$ncategory_id . "' ORDER BY level ASC");

		if ($query->rows) {
			foreach ($query->rows as $ncategory_path) {
				// Delete the path below the current one
				$this->db->query("DELETE FROM `" . DB_PREFIX . "ncategory_path` WHERE ncategory_id = '" . (int)$ncategory_path['ncategory_id'] . "' AND level < '" . (int)$ncategory_path['level'] . "'");

				$path = array();

				// Get the nodes new parents
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ncategory_path` WHERE ncategory_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Get whats left of the nodes current path
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ncategory_path` WHERE ncategory_id = '" . (int)$ncategory_path['ncategory_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Combine the paths with a new level
				$level = 0;

				foreach ($path as $path_id) {
					$this->db->query("REPLACE INTO `" . DB_PREFIX . "ncategory_path` SET ncategory_id = '" . (int)$ncategory_path['ncategory_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");

					$level++;
				}
			}
		} else {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "ncategory_path` WHERE ncategory_id = '" . (int)$ncategory_id . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ncategory_path` WHERE ncategory_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "ncategory_path` SET ncategory_id = '" . (int)$ncategory_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "ncategory_path` SET ncategory_id = '" . (int)$ncategory_id . "', `path_id` = '" . (int)$ncategory_id . "', level = '" . (int)$level . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "ncategory_filter WHERE ncategory_id = '" . (int)$ncategory_id . "'");

		if (isset($data['ncategory_filter'])) {
			foreach ($data['ncategory_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ncategory_filter SET ncategory_id = '" . (int)$ncategory_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "ncategory_to_store WHERE ncategory_id = '" . (int)$ncategory_id . "'");

		if (isset($data['ncategory_store'])) {
			foreach ($data['ncategory_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ncategory_to_store SET ncategory_id = '" . (int)$ncategory_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "ncategory_to_layout WHERE ncategory_id = '" . (int)$ncategory_id . "'");

		if (isset($data['ncategory_layout'])) {
			foreach ($data['ncategory_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ncategory_to_layout SET ncategory_id = '" . (int)$ncategory_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'ncategory_id=" . (int)$ncategory_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'ncategory_id=" . (int)$ncategory_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('ncategory');

		$this->event->trigger('post.admin.ncategory.edit', $ncategory_id);
	}

	public function deletencategory($ncategory_id) {
		$this->event->trigger('pre.admin.ncategory.delete', $ncategory_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "ncategory_path WHERE ncategory_id = '" . (int)$ncategory_id . "'");

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ncategory_path WHERE path_id = '" . (int)$ncategory_id . "'");

		foreach ($query->rows as $result) {
			$this->deletencategory($result['ncategory_id']);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "ncategory WHERE ncategory_id = '" . (int)$ncategory_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ncategory_description WHERE ncategory_id = '" . (int)$ncategory_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ncategory_filter WHERE ncategory_id = '" . (int)$ncategory_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ncategory_to_store WHERE ncategory_id = '" . (int)$ncategory_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "ncategory_to_layout WHERE ncategory_id = '" . (int)$ncategory_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_ncategory WHERE ncategory_id = '" . (int)$ncategory_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'ncategory_id=" . (int)$ncategory_id . "'");

		$this->cache->delete('ncategory');

		$this->event->trigger('post.admin.ncategory.delete', $ncategory_id);
	}

	public function repairCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ncategory WHERE parent_id = '" . (int)$parent_id . "'");

		foreach ($query->rows as $ncategory) {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "ncategory_path` WHERE ncategory_id = '" . (int)$ncategory['ncategory_id'] . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ncategory_path` WHERE ncategory_id = '" . (int)$parent_id . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "ncategory_path` SET ncategory_id = '" . (int)$ncategory['ncategory_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "ncategory_path` SET ncategory_id = '" . (int)$ncategory['ncategory_id'] . "', `path_id` = '" . (int)$ncategory['ncategory_id'] . "', level = '" . (int)$level . "'");

			$this->repairCategories($ncategory['ncategory_id']);
		}
	}

	public function getncategory($ncategory_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "ncategory_path cp LEFT JOIN " . DB_PREFIX . "ncategory_description cd1 ON (cp.path_id = cd1.ncategory_id AND cp.ncategory_id != cp.path_id) WHERE cp.ncategory_id = c.ncategory_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.ncategory_id) AS path, (SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'ncategory_id=" . (int)$ncategory_id . "') AS keyword FROM " . DB_PREFIX . "ncategory c LEFT JOIN " . DB_PREFIX . "ncategory_description cd2 ON (c.ncategory_id = cd2.ncategory_id) WHERE c.ncategory_id = '" . (int)$ncategory_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getCategories($data = array()) {
		$sql = "SELECT cp.ncategory_id AS ncategory_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "ncategory_path cp LEFT JOIN " . DB_PREFIX . "ncategory c1 ON (cp.ncategory_id = c1.ncategory_id) LEFT JOIN " . DB_PREFIX . "ncategory c2 ON (cp.path_id = c2.ncategory_id) LEFT JOIN " . DB_PREFIX . "ncategory_description cd1 ON (cp.path_id = cd1.ncategory_id) LEFT JOIN " . DB_PREFIX . "ncategory_description cd2 ON (cp.ncategory_id = cd2.ncategory_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " GROUP BY cp.ncategory_id";

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sort_order";
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

	public function getncategoryDescriptions($ncategory_id) {
		$ncategory_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ncategory_description WHERE ncategory_id = '" . (int)$ncategory_id . "'");

		foreach ($query->rows as $result) {
			$ncategory_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'description'      => $result['description']
			);
		}

		return $ncategory_description_data;
	}

	public function getncategoryFilters($ncategory_id) {
		$ncategory_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ncategory_filter WHERE ncategory_id = '" . (int)$ncategory_id . "'");

		foreach ($query->rows as $result) {
			$ncategory_filter_data[] = $result['filter_id'];
		}

		return $ncategory_filter_data;
	}

	public function getncategoryStores($ncategory_id) {
		$ncategory_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ncategory_to_store WHERE ncategory_id = '" . (int)$ncategory_id . "'");

		foreach ($query->rows as $result) {
			$ncategory_store_data[] = $result['store_id'];
		}

		return $ncategory_store_data;
	}

	public function getncategoryLayouts($ncategory_id) {
		$ncategory_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ncategory_to_layout WHERE ncategory_id = '" . (int)$ncategory_id . "'");

		foreach ($query->rows as $result) {
			$ncategory_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $ncategory_layout_data;
	}

	public function getTotalCategories() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ncategory");

		return $query->row['total'];
	}
	
	public function getTotalCategoriesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ncategory_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}	
}
