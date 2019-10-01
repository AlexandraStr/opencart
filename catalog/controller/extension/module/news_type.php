<?php  
class ControllerExtensionModuleNewsType extends Controller {
	public function index() {
		$this->language->load('extension/news');
		$this->load->model('extension/news');
	 
		$data['heading_title'] = $this->language->get('heading_title');

        $type_news =  $this->model_extension_news->getAllTypeNames();

        $data['menu_news'] = array();

        foreach($type_news as $type_new){
            if($type_new['type_id']==10){
                $data['menu_news_name']= array(
                    'type_name' => $type_new['type_name'],
                    'href' => $this->url->link('extension/news', 'type=' . (int)$type_new['type_id'])
                );
            }else {
                $data['menu_news'][] = array(
                    'type_id' => $type_new['type_id'],
                    'type_name' => $type_new['type_name'],
                    'href' => $this->url->link('extension/news', 'type=' . (int)$type_new['type_id'])

                );
            }
        }
		
		return $this->load->view('extension/module/news_type', $data);
	}
}