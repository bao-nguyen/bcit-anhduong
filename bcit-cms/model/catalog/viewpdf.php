<?php
class ModelCatalogviewpdf extends Model {
	public function addviewpdf($data) {
		$this->event->trigger('pre.admin.viewpdf.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "viewpdf SET filename = '" . $this->db->escape($data['filename']) . "', mask = '" . $this->db->escape($data['mask']) . "', date_added = NOW()");

		$viewpdf_id = $this->db->getLastId();

		foreach ($data['viewpdf_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "viewpdf_description SET viewpdf_id = '" . (int)$viewpdf_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->event->trigger('post.admin.viewpdf.add', $viewpdf_id);

		return $viewpdf_id;
	}

	public function editviewpdf($viewpdf_id, $data) {
		$this->event->trigger('pre.admin.viewpdf.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "viewpdf SET filename = '" . $this->db->escape($data['filename']) . "', mask = '" . $this->db->escape($data['mask']) . "' WHERE viewpdf_id = '" . (int)$viewpdf_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "viewpdf_description WHERE viewpdf_id = '" . (int)$viewpdf_id . "'");

		foreach ($data['viewpdf_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "viewpdf_description SET viewpdf_id = '" . (int)$viewpdf_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->event->trigger('post.admin.viewpdf.edit', $viewpdf_id);
	}

	public function deleteviewpdf($viewpdf_id) {
		$this->event->trigger('pre.admin.viewpdf.delete', $viewpdf_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "viewpdf WHERE viewpdf_id = '" . (int)$viewpdf_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "viewpdf_description WHERE viewpdf_id = '" . (int)$viewpdf_id . "'");

		$this->event->trigger('post.admin.viewpdf.delete', $viewpdf_id);
	}

	public function getviewpdf($viewpdf_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "viewpdf d LEFT JOIN " . DB_PREFIX . "viewpdf_description dd ON (d.viewpdf_id = dd.viewpdf_id) WHERE d.viewpdf_id = '" . (int)$viewpdf_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getviewpdfs($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "viewpdf d LEFT JOIN " . DB_PREFIX . "viewpdf_description dd ON (d.viewpdf_id = dd.viewpdf_id) WHERE dd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

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

	public function getviewpdfDescriptions($viewpdf_id) {
		$viewpdf_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "viewpdf_description WHERE viewpdf_id = '" . (int)$viewpdf_id . "'");

		foreach ($query->rows as $result) {
			$viewpdf_description_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $viewpdf_description_data;
	}

	public function getTotalviewpdfs() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "viewpdf");

		return $query->row['total'];
	}
}