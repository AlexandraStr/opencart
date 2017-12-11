<?php

class ControllerExtensionModuleLiveSearch extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('extension/module/live_search');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('module_live_search', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['limit'])) {
			$data['error_limit'] = $this->error['limit'];
		} else {
			$data['error_limit'] = '';
		}
		if (isset($this->error['width'])) {
			$data['error_width'] = $this->error['width'];
		} else {
			$data['error_width'] = '';
		}
		if (isset($this->error['height'])) {
			$data['error_height'] = $this->error['height'];
		} else {
			$data['error_height'] = '';
		}
		if (isset($this->error['title_length'])) {
			$data['error_title_length'] = $this->error['title_length'];
		} else {
			$data['error_title_length'] = '';
		}
		if (isset($this->error['description_length'])) {
			$data['error_description_length'] = $this->error['description_length'];
		} else {
			$data['error_description_length'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		    unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}


		// Languages
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_success'] = $this->language->get('text_success');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_title_length'] = $this->language->get('entry_title_length');
		$data['entry_description_length'] = $this->language->get('entry_description_length');
		$data['entry_show_image'] = $this->language->get('entry_show_image');
		$data['entry_show_price'] = $this->language->get('entry_show_price');
		$data['entry_show_description'] = $this->language->get('entry_show_description');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['help_length'] = $this->language->get('help_length');

        //Хлебные крошки
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/live_search', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/module/live_search', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);


        // $data['user_token'] = $this->session->data['user_token'];
		if (isset($this->request->post['module_live_search_limit'])) {
			$data['module_live_search_limit'] = $this->request->post['module_live_search_limit'];
		} else {
			$data['module_live_search_limit'] = $this->config->get('module_live_search_limit');
		}
		if (isset($this->request->post['module_live_search_image_width'])) {
			$data['module_live_search_image_width'] = $this->request->post['module_live_search_image_width'];
		} else {
			$data['module_live_search_image_width'] = $this->config->get('module_live_search_image_width');
		}
		if (isset($this->request->post['module_live_search_image_height'])) {
			$data['module_live_search_image_height'] = $this->request->post['module_live_search_image_height'];
		} else {
			$data['module_live_search_image_height'] = $this->config->get('module_live_search_image_height');
		}
		if (isset($this->request->post['module_live_search_title_length'])) {
			$data['module_live_search_title_length'] = $this->request->post['module_live_search_title_length'];
		} else {
			$data['module_live_search_title_length'] = $this->config->get('module_live_search_title_length');
		}
		if (isset($this->request->post['module_live_search_description_length'])) {
			$data['module_live_search_description_length'] = $this->request->post['module_live_search_description_length'];
		} else {
			$data['module_live_search_description_length'] = $this->config->get('module_live_search_description_length');
		}

		if (isset($this->request->post['module_live_search_show_image'])) {
			$data['module_live_search_show_image'] = $this->request->post['module_live_search_show_image'];
		} else {
			$data['module_live_search_show_image'] = $this->config->get('module_live_search_show_image');
		}
		if (isset($this->request->post['module_live_search_show_price'])) {
			$data['module_live_search_show_price'] = $this->request->post['module_live_search_show_price'];
		} else {
			$data['module_live_search_show_price'] = $this->config->get('module_live_search_show_price');
		}
		if (isset($this->request->post['module_live_search_show_description'])) {
			$data['module_live_search_show_description'] = $this->request->post['module_live_search_show_description'];
		} else {
			$data['module_live_search_show_description'] = $this->config->get('module_live_search_show_description');
		}
		if (isset($this->request->post['module_live_search_status'])) {
			$data['module_live_search_status'] = $this->request->post['module_live_search_status'];
		} else {
			$data['module_live_search_status'] = $this->config->get('module_live_search_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
        $data['current_lang_id'] = $this->config->get('config_language_id');

		$this->response->setOutput($this->load->view('extension/module/live_search', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/live_search')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
        if (!$this->request->post['module_live_search_limit']) {
			$this->error['limit'] = $this->language->get('error_limit');
		}
		if (!$this->request->post['module_live_search_image_width']) {
			$this->error['width'] = $this->language->get('error_width');
		}
		if (!$this->request->post['module_live_search_image_height']) {
			$this->error['height'] = $this->language->get('error_height');
		}
		if (!$this->request->post['module_live_search_title_length']) {
			$this->error['title_length'] = $this->language->get('error_title_length');
		}
		if (!$this->request->post['module_live_search_description_length']) {
			$this->error['description_length'] = $this->language->get('error_description_length');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

}
