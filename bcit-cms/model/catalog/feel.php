<?php
class ModelCatalogFeel extends Model {
	public function addFeel($data) {
		$this->event->trigger('pre.admin.feel.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "feel SET date_added = NOW(), status = '" . (int)$data['status'] . "'");

		$feel_id = $this->db->getLastId();
        
        if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "feel SET image = '" . $this->db->escape($data['image']) . "' WHERE feel_id = '" . (int)$feel_id . "'");
		}
        
		foreach ($data['feel_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "feel_description SET feel_id = '" . (int)$feel_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', company = '" . $this->db->escape($value['company']) . "', feel = '" . $this->db->escape($value['feel']) . "'");
		}                

		$this->event->trigger('post.admin.feel.add', $feel_id);

		return $feel_id;
	}

	public function editfeel($feel_id, $data) {
		$this->event->trigger('pre.admin.feel.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "feel SET status = '" . (int)$data['status'] . "' WHERE feel_id = '" . (int)$feel_id . "'");
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "feel SET image = '" . $this->db->escape($data['image']) . "' WHERE feel_id = '" . (int)$feel_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "feel_description WHERE feel_id = '" . (int)$feel_id . "'");

		foreach ($data['feel_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "feel_description SET feel_id = '" . (int)$feel_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', company = '" . $this->db->escape($value['company']) . "', feel = '" . $this->db->escape($value['feel']) . "'");
		}                

		$this->event->trigger('post.admin.feel.edit', $feel_id);
	}

	public function deletefeel($feel_id) {
		$this->event->trigger('pre.admin.feel.delete', $feel_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "feel WHERE feel_id = '" . (int)$feel_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "feel_description WHERE feel_id = '" . (int)$feel_id . "'");

		$this->event->trigger('post.admin.feel.delete', $feel_id);
	}

	public function getfeel($feel_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "feel d LEFT JOIN " . DB_PREFIX . "feel_description dd ON (d.feel_id = dd.feel_id) WHERE d.feel_id = '" . (int)$feel_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getfeels($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "feel d LEFT JOIN " . DB_PREFIX . "feel_description dd ON (d.feel_id = dd.feel_id) WHERE dd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND dd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'dd.name',
			'd.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY dd.name";
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

	public function getfeelDescriptions($feel_id) {
		$feel_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "feel_description WHERE feel_id = '" . (int)$feel_id . "'");

		foreach ($query->rows as $result) {
			$feel_description_data[$result['language_id']] = array(
                'name' => $result['name'],
                'company' => $result['company'],
                'feel' => $result['feel'],
            );            
		}
               
		return $feel_description_data;
	}

	public function getTotalfeels() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "feel");

		return $query->row['total'];
	}
}