<?php
class ControllerCommonSearch extends Controller {
	public function index() {
        $this->load->language('product/search');
                $data['text_view_all_results'] = $this->language->get('text_view_all_results');
                $data['text_empty'] = $this->language->get('text_empty');
                $data['module_live_search_status'] = $this->config->get('module_live_search_status');
                $data['module_live_search_show_image'] = $this->config->get('module_live_search_show_image');
                $data['module_live_search_show_price'] = $this->config->get('module_live_search_show_price');
                $data['module_live_search_show_description'] = $this->config->get('module_live_search_show_description');
                $data['live_search_href'] = $this->url->link('product/search', 'search=');

		$this->load->language('common/search');

		$data['text_search'] = $this->language->get('text_search');

		if (isset($this->request->get['search'])) {
			$data['search'] = $this->request->get['search'];
		} else {
			$data['search'] = '';
		}

		return $this->load->view('common/search', $data);
	}
}