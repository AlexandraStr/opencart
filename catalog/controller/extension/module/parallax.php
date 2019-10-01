<?php

class ControllerExtensionModuleParallax extends Controller {

    public function index($setting) {
        if (isset($setting['module_description'][$this->config->get('config_language_id')])) {

            $this->load->model('tool/image');
            $data['image'] = $this->model_tool_image->resize($setting['image'],900,900);
            $data['html'] = $setting['html'];
            if ($setting['html']) {                
                $data['html_content'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['html_content'], ENT_QUOTES, 'UTF-8');
            } else {
                $data['text'] = $setting['module_description'][$this->config->get('config_language_id')]['text'];
                $data['catch_phrase'] = $setting['module_description'][$this->config->get('config_language_id')]['catch_phrase'];
            }


            return $this->load->view('extension/module/parallax', $data);
        }
    }

}
