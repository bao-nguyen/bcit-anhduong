<?php
class ModelCatalogMenu extends Model {
	public function addMenu($data) {
		$this->event->trigger('pre.admin.menu.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "menu SET parent_id = '" . (int)$data['parent_id'] . "', `link` = '" . (isset($data['link']) ? $data['link'] : '') . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");

		$menu_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "menu SET image = '" . $this->db->escape($data['image']) . "' WHERE menu_id = '" . (int)$menu_id . "'");
		}

		foreach ($data['menu_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "menu_description SET menu_id = '" . (int)$menu_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$level = 0;

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_path` WHERE menu_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");

		foreach ($query->rows as $result) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "menu_path` SET `menu_id` = '" . (int)$menu_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

			$level++;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "menu_path` SET `menu_id` = '" . (int)$menu_id . "', `path_id` = '" . (int)$menu_id . "', `level` = '" . (int)$level . "'");		

		if (isset($data['menu_store'])) {
			foreach ($data['menu_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "menu_to_store SET menu_id = '" . (int)$menu_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		// Set which layout to use with this menu
		if (isset($data['menu_layout'])) {
			foreach ($data['menu_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "menu_to_layout SET menu_id = '" . (int)$menu_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->cache->delete('menu');

		$this->event->trigger('post.admin.menu.add', $menu_id);

		return $menu_id;
	}

	public function editmenu($menu_id, $data) {
		$this->event->trigger('pre.admin.menu.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "menu SET parent_id = '" . (int)$data['parent_id'] . "', `link` = '" . (isset($data['link']) ? $data['link'] : '') . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE menu_id = '" . (int)$menu_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "menu SET image = '" . $this->db->escape($data['image']) . "' WHERE menu_id = '" . (int)$menu_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_description WHERE menu_id = '" . (int)$menu_id . "'");

		foreach ($data['menu_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "menu_description SET menu_id = '" . (int)$menu_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_path` WHERE path_id = '" . (int)$menu_id . "' ORDER BY level ASC");

		if ($query->rows) {
			foreach ($query->rows as $menu_path) {
				// Delete the path below the current one
				$this->db->query("DELETE FROM `" . DB_PREFIX . "menu_path` WHERE menu_id = '" . (int)$menu_path['menu_id'] . "' AND level < '" . (int)$menu_path['level'] . "'");

				$path = array();

				// Get the nodes new parents
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_path` WHERE menu_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Get whats left of the nodes current path
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_path` WHERE menu_id = '" . (int)$menu_path['menu_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Combine the paths with a new level
				$level = 0;

				foreach ($path as $path_id) {
					$this->db->query("REPLACE INTO `" . DB_PREFIX . "menu_path` SET menu_id = '" . (int)$menu_path['menu_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");

					$level++;
				}
			}
		} else {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "menu_path` WHERE menu_id = '" . (int)$menu_id . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_path` WHERE menu_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "menu_path` SET menu_id = '" . (int)$menu_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "menu_path` SET menu_id = '" . (int)$menu_id . "', `path_id` = '" . (int)$menu_id . "', level = '" . (int)$level . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_to_store WHERE menu_id = '" . (int)$menu_id . "'");

		if (isset($data['menu_store'])) {
			foreach ($data['menu_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "menu_to_store SET menu_id = '" . (int)$menu_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_to_layout WHERE menu_id = '" . (int)$menu_id . "'");

		if (isset($data['menu_layout'])) {
			foreach ($data['menu_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "menu_to_layout SET menu_id = '" . (int)$menu_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}		

		$this->cache->delete('menu');

		$this->event->trigger('post.admin.menu.edit', $menu_id);
	}

	public function deletemenu($menu_id) {
		$this->event->trigger('pre.admin.menu.delete', $menu_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_path WHERE menu_id = '" . (int)$menu_id . "'");

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_path WHERE path_id = '" . (int)$menu_id . "'");

		foreach ($query->rows as $result) {
			$this->deletemenu($result['menu_id']);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "menu WHERE menu_id = '" . (int)$menu_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_description WHERE menu_id = '" . (int)$menu_id . "'");		
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_to_store WHERE menu_id = '" . (int)$menu_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_to_layout WHERE menu_id = '" . (int)$menu_id . "'");
        		
		$this->cache->delete('menu');

		$this->event->trigger('post.admin.menu.delete', $menu_id);
	}

	public function repairmenus($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu WHERE parent_id = '" . (int)$parent_id . "'");

		foreach ($query->rows as $menu) {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "menu_path` WHERE menu_id = '" . (int)$menu['menu_id'] . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "menu_path` WHERE menu_id = '" . (int)$parent_id . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "menu_path` SET menu_id = '" . (int)$menu['menu_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "menu_path` SET menu_id = '" . (int)$menu['menu_id'] . "', `path_id` = '" . (int)$menu['menu_id'] . "', level = '" . (int)$level . "'");

			$this->repairmenus($menu['menu_id']);
		}
	}

	public function getmenu($menu_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "menu_path cp LEFT JOIN " . DB_PREFIX . "menu_description cd1 ON (cp.path_id = cd1.menu_id AND cp.menu_id != cp.path_id) WHERE cp.menu_id = c.menu_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.menu_id) AS path, (SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'menu_id=" . (int)$menu_id . "') AS keyword FROM " . DB_PREFIX . "menu c LEFT JOIN " . DB_PREFIX . "menu_description cd2 ON (c.menu_id = cd2.menu_id) WHERE c.menu_id = '" . (int)$menu_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getmenus($data = array()) {
		$sql = "SELECT cp.menu_id AS menu_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "menu_path cp LEFT JOIN " . DB_PREFIX . "menu c1 ON (cp.menu_id = c1.menu_id) LEFT JOIN " . DB_PREFIX . "menu c2 ON (cp.path_id = c2.menu_id) LEFT JOIN " . DB_PREFIX . "menu_description cd1 ON (cp.path_id = cd1.menu_id) LEFT JOIN " . DB_PREFIX . "menu_description cd2 ON (cp.menu_id = cd2.menu_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " GROUP BY cp.menu_id";

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

	public function getmenuDescriptions($menu_id) {
		$menu_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_description WHERE menu_id = '" . (int)$menu_id . "'");

		foreach ($query->rows as $result) {
			$menu_description_data[$result['language_id']] = array(
				'name'             => $result['name'],				
				'description'      => $result['description']
			);
		}

		return $menu_description_data;
	}
	

	public function getmenuStores($menu_id) {
		$menu_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_to_store WHERE menu_id = '" . (int)$menu_id . "'");

		foreach ($query->rows as $result) {
			$menu_store_data[] = $result['store_id'];
		}

		return $menu_store_data;
	}

	public function getmenuLayouts($menu_id) {
		$menu_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_to_layout WHERE menu_id = '" . (int)$menu_id . "'");

		foreach ($query->rows as $result) {
			$menu_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $menu_layout_data;
	}

	public function getTotalmenus() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "menu");

		return $query->row['total'];
	}
	
	public function getTotalmenusByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "menu_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}	
}
