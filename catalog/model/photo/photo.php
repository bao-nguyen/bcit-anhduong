<?php
class ModelPhotoPhoto extends Model {
	
	public function getAlbumImages($photo_id) {
		$photo_image_data = array();

		$photo_image_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "photo_image WHERE photo_id = '" . (int)$photo_id . "' ORDER BY sort_order ASC");

		foreach ($photo_image_query->rows as $photo_image) {
			$photo_image_description_data = array();

			$photo_image_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "photo_image_description WHERE photo_image_id = '" . (int)$photo_image['photo_image_id'] . "' AND photo_id = '" . (int)$photo_id . "'");

			foreach ($photo_image_description_query->rows as $photo_image_description) {
				$photo_image_description_data[$photo_image_description['language_id']] = array('title' => $photo_image_description['title']);
			}

			$photo_image_data[] = array(
				'photo_image_description' => $photo_image_description_data,			
				'image'                    => $photo_image['image'],
				'sort_order'               => $photo_image['sort_order']
			);
		}

		return $photo_image_data;
	}	
}
