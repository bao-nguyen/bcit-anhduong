<?php
class ModelCatalogForm extends Model {
	public function addform($data) {
		$this->event->trigger('pre.admin.form.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "form SET filename = '" . $this->db->escape($data['filename']) . "', mask = '" . $this->db->escape($data['mask']) . "', date_added = NOW()");

		$form_id = $this->db->getLastId();

		foreach ($data['form_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "form_description SET form_id = '" . (int)$form_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->event->trigger('post.admin.form.add', $form_id);

		return $form_id;
	}

	public function editform($form_id, $data) {
		$this->event->trigger('pre.admin.form.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "form SET filename = '" . $this->db->escape($data['filename']) . "', mask = '" . $this->db->escape($data['mask']) . "' WHERE form_id = '" . (int)$form_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "form_description WHERE form_id = '" . (int)$form_id . "'");

		foreach ($data['form_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "form_description SET form_id = '" . (int)$form_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->event->trigger('post.admin.form.edit', $form_id);
	}

	public function deleteform($form_id) {
		$this->event->trigger('pre.admin.form.delete', $form_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "form WHERE form_id = '" . (int)$form_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "form_description WHERE form_id = '" . (int)$form_id . "'");

		$this->event->trigger('post.admin.form.delete', $form_id);
	}

	public function getform($form_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "form d LEFT JOIN " . DB_PREFIX . "form_description dd ON (d.form_id = dd.form_id) WHERE d.form_id = '" . (int)$form_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getforms($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "form d LEFT JOIN " . DB_PREFIX . "form_description dd ON (d.form_id = dd.form_id) WHERE dd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

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

	public function getformDescriptions($form_id) {
		$form_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "form_description WHERE form_id = '" . (int)$form_id . "'");

		foreach ($query->rows as $result) {
			$form_description_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $form_description_data;
	}

	public function getTotalforms() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "form");

		return $query->row['total'];
	}
}