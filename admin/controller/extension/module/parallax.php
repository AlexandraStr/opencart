<?php

class ControllerExtensionModuleParallax extends Controller {

    private $error = array();


    public function index() {
        $this->load->language('extension/module/parallax');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        $this->load->model('tool/image');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('parallax', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_html'] = $this->language->get('entry_html');
        $data['entry_catch_phrase'] = $this->language->get('entry_catch_phrase');
        $data['entry_text'] = $this->language->get('entry_text');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_html_content'] = $this->language->get('entry_html_content');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }
        if (isset($this->error['html'])) {
            $data['error_html'] = $this->error['html'];
        } else {
            $data['error_html'] = '';
        }
        if (isset($this->error['image'])) {
            $data['error_image'] = $this->error['image'];
        } else {
            $data['error_image'] = '';
        }
        if (isset($this->error['content'])) {
            $data['error_content'] = $this->error['content'];
        } else {
            $data['error_content'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/parallax', 'user_token=' . $this->session->data['user_token'], true)
        );



        $data['action'] = $this->url->link('extension/module/parallax', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
        }

        if (isset($this->request->post['parallax_name'])) {
            $data['parallax_name'] = $this->request->post['parallax_name'];
        } elseif (!empty($module_info)) {
            $data['parallax_name'] = $module_info['parallax_name'];
        } else {
            $data['parallax_name'] = '';
        }
        if (isset($this->request->post['parallax_image'])) {
            $data['parallax_image'] = $this->request->post['parallax_image'];
        } elseif (!empty($module_info)) {
            $data['parallax_image'] = $module_info['parallax_image'];
        } else {
            $data['parallax_image'] = '';
        }

        if (isset($this->request->post['parallax_image']) && is_file(DIR_IMAGE . $this->request->post['parallax_image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['parallax_image'], 100, 100);
        } elseif (!empty($module_info) && is_file(DIR_IMAGE . $module_info['parallax_image'])) {
            $data['thumb'] = $this->model_tool_image->resize($module_info['parallax_image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }
        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);


        if (isset($this->request->post['html'])) {
            $data['parallax_html'] = $this->request->post['parallax_html'];
        } elseif (!empty($module_info)) {
            $data['parallax_html'] = $module_info['parallax_html'];
        } else {
            $data['parallax_html'] = 0;
        }
        if (isset($this->request->post['parallax_status'])) {
            $data['parallax_status'] = $this->request->post['parallax_status'];
        } elseif (!empty($module_info)) {
            $data['parallax_status'] = $module_info['parallax_status'];
        } else {
            $data['parallax_status'] = 1;
        }

        if (isset($this->request->post['parallax_module_description'])) {
            $data['parallax_description'] = $this->request->post['parallax_description'];
        } elseif (!empty($module_info)) {
            $data['parallax_description'] = $module_info['parallax_description'];
        } else {
            $data['parallax_description'] = array();
        }

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/parallax', $data));
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/parallax')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['parallax_name']) < 3) || (utf8_strlen($this->request->post['parallax_name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }
        if (!file_exists(DIR_IMAGE.$this->request->post['parallax_image']) || is_dir(DIR_IMAGE.$this->request->post['parallax_image'])) {
            $this->error['image'] = $this->language->get('error_image');
        }

        return !$this->error;
    }

    public function install() {
        $this->load->model("localisation/language");
        $this->load->language("extension/module/parallax");

    }

}
