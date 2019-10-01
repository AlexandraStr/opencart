<?php
namespace d_cache;

class File {
	public function get($group, $key) {
		if (file_exists(DIR_CACHE . '/' . $group . '/')) {
			$files = glob(DIR_CACHE . '/' . $group . '/' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

			if ($files) {
				$time = substr(strrchr($files[0], '.'), 1);

				if ($time && ($time < time())) {
					unlink($file);
				} else {
					$handle = fopen($files[0], 'r');

					flock($handle, LOCK_SH);

					$data = fread($handle, filesize($files[0]));

					flock($handle, LOCK_UN);

					fclose($handle);

					return json_decode($data, true);
				}
			}
		}

		return false;
	}

	public function set($group, $key, $value, $expire = 0) {		
		$this->delete($group, $key);
		
		if (!file_exists(DIR_CACHE . '/' . $group . '/')) {
			mkdir(DIR_CACHE . '/' . $group . '/', 0777, true);
		}
		
		$file = DIR_CACHE . '/' . $group . '/' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.' . ($expire ? (time() + $expire) : $expire);

		$handle = fopen($file, 'w');

		flock($handle, LOCK_EX);

		fwrite($handle, json_encode($value));

		fflush($handle);

		flock($handle, LOCK_UN);

		fclose($handle);
	}

	public function delete($group, $key) {
		if (file_exists(DIR_CACHE . '/' . $group . '/')) {
			$files = glob(DIR_CACHE . '/' . $group . '/' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

			if ($files) {
				foreach ($files as $file) {
					if (file_exists($file)) {
						unlink($file);
					}
				}
			}
		}
	}
	
	public function deleteAll($group) {
		if (file_exists(DIR_CACHE . '/' . $group . '/')) {
			$files = glob(DIR_CACHE . '/' . $group . '/*');

			if ($files) {
				foreach ($files as $file) {
					if (file_exists($file)) {
						unlink($file);
					}
				}
			}
			
			unlink(DIR_CACHE . '/' . $group . '/');
		}
	}
	
	public function deleteOld($group) {
		if (file_exists(DIR_CACHE . '/' . $group . '/')) {
			$files = glob(DIR_CACHE . '/' . $group . '/*');

			if ($files) {
				foreach ($files as $file) {
					$time = substr(strrchr($file, '.'), 1);

					if ($time && ($time < time())) {
						if (file_exists($file)) {
							unlink($file);
						}
					}
				}
			}
			
			$files = glob(DIR_CACHE . '/' . $group . '/*');
			
			if (!$files) {
				unlink(DIR_CACHE . '/' . $group . '/');
			}
		}
	}
}