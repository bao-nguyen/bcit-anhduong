<?php
class ModelDesignphoto extends Model {
	public function addphoto($data) {
		$this->event->trigger('pre.admin.photo.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "photo SET status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");        
        
		$photo_id = $this->db->getLastId();
        
        foreach ($data['photo_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "photo_description SET photo_id = '" . (int)$photo_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		if (isset($data['photo_image'])) {
			foreach ($data['photo_image'] as $photo_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "photo_image SET photo_id = '" . (int)$photo_id . "', image = '" .  $this->db->escape($photo_image['image']) . "', sort_order = '" . (int)$photo_image['sort_order'] . "'");

				$photo_image_id = $this->db->getLastId();

				foreach ($photo_image['photo_image_description'] as $language_id => $photo_image_description) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "photo_image_description SET photo_image_id = '" . (int)$photo_image_id . "', language_id = '" . (int)$language_id . "', photo_id = '" . (int)$photo_id . "', title = '" .  $this->db->escape($photo_image_description['title']) . "'");
				}
			}
		}

		$this->event->trigger('post.admin.photo.add', $photo_id);

		return $photo_id;
	}

	public function editphoto($photo_id, $data) {
		$this->event->trigger('pre.admin.photo.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "photo SET status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE photo_id = '" . (int)$photo_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "photo_description WHERE photo_id = '" . (int)$photo_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "photo_image WHERE photo_id = '" . (int)$photo_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "photo_image_description WHERE photo_id = '" . (int)$photo_id . "'");

        foreach ($data['photo_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "photo_description SET photo_id = '" . (int)$photo_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
        
		if (isset($data['photo_image'])) {
			foreach ($data['photo_image'] as $photo_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "photo_image SET photo_id = '" . (int)$photo_id . "', image = '" .  $this->db->escape($photo_image['image']) . "', sort_order = '" . (int)$photo_image['sort_order'] . "'");

				$photo_image_id = $this->db->getLastId();

				foreach ($photo_image['photo_image_description'] as $language_id => $photo_image_description) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "photo_image_description SET photo_image_id = '" . (int)$photo_image_id . "', language_id = '" . (int)$language_id . "', photo_id = '" . (int)$photo_id . "', title = '" .  $this->db->escape($photo_image_description['title']) . "'");
				}
			}
		}

		$this->event->trigger('post.admin.photo.edit', $photo_id);
	}

	public function deletephoto($photo_id) {
		$this->event->trigger('pre.admin.photo.delete', $photo_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "photo WHERE photo_id = '" . (int)$photo_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "photo_description WHERE photo_id = '" . (int)$photo_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "photo_image WHERE photo_id = '" . (int)$photo_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "photo_image_description WHERE photo_id = '" . (int)$photo_id . "'");

		$this->event->trigger('post.admin.photo.delete', $photo_id);
	}

	public function getphoto($photo_id) {
		//$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "photo WHERE photo_id = '" . (int)$photo_id . "'");
          $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "photo p LEFT JOIN " . DB_PREFIX . "photo_description pd ON (p.photo_id = pd.photo_id) WHERE p.photo_id = '" . (int)$photo_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		return $query->row;
	}

	public function getphotos($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "photo d LEFT JOIN " . DB_PREFIX . "photo_description dd ON (d.photo_id = dd.photo_id) WHERE dd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND dd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}
        
        $sort_data = array(
			'dd.name',
			'd.date_added'
		);
        /*
        $sql = "SELECT * FROM " . DB_PREFIX . "photo";

		$sort_data = array(
			'name',
			'status'
		);
        */
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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

	public function getphotoImages($photo_id) {
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

    public function getPhotoDescriptions($photo_id) {
		$photo_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "photo_description WHERE photo_id = '" . (int)$photo_id . "'");

		foreach ($query->rows as $result) {
			$photo_description_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $photo_description_data;
	}
    
	public function getTotalphotos() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "photo");

		return $query->row['total'];
	}
}