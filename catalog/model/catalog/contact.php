<?php
class ModelCatalogContact extends Model {
	public function addContact($data) {		

		$this->db->query("INSERT INTO " . DB_PREFIX . "contact SET `name` = '" . $this->db->escape($data['name']) . "', `email` = '" . $this->db->escape($data['email']) . "', content = '" . $this->db->escape($data['enquiry']) . "', is_read = '0', date_modified = NOW(), date_added = NOW()");

	}
}