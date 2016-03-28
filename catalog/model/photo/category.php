<?php
class ModelPhotoCategory extends Model {
	public function getAlbums() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "photo p LEFT JOIN " . DB_PREFIX . "photo_description pd ON (p.photo_id = pd.photo_id) WHERE p.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
        
		return $query->rows;
	}
    
    public function getAlbum($album_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "photo p LEFT JOIN " . DB_PREFIX . "photo_description pd ON (p.photo_id = pd.photo_id) WHERE p.photo_id = '" . $album_id . "' AND p.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
        
		return $query->rows;
	}
}