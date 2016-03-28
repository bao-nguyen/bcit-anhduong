<?php
class ModelCatalogFeel extends Model {
	

	public function getFeels($limit) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "feel f LEFT JOIN " . DB_PREFIX . "feel_description fd ON (f.feel_id = fd.feel_id) WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND f.status = '1' ORDER BY f.date_added LIMIT 0," . $limit);

		return $query->rows;
	}

	
}