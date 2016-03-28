<?php
class ModelCatalogBcitDownload extends Model {
	
    public function getForms() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "form d LEFT JOIN " . DB_PREFIX . "form_description dd ON (d.form_id = dd.form_id) WHERE dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
        
		return $query->rows;
	}

	public function getForm($form_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "form d LEFT JOIN " . DB_PREFIX . "form_description dd ON (d.form_id = dd.form_id) WHERE dd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND d.form_id = '" . (int)$form_id . "'");
                                                                                                                                                                            
		return $query->rows;
	}
    
    public function getDownloadsByNewsId($news_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "news_to_download` n2d LEFT JOIN " . DB_PREFIX . "download d ON (n2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (dd.download_id = d.download_id) WHERE n2d.news_id = '" . (int)$news_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");                            
        return $query->rows;	                            
    }
    
    public function getDownload($download_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download d LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE dd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND d.download_id = '" . (int)$download_id . "'");
                                                                                                                                                                            
		return $query->rows;
	}
}