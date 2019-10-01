<?php
class ControllerCommonMenu extends Controller {
	public function index() {


	    $this->load->language('common/menu');

		// Menu
        $this->load->model('extension/news');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

        $data['menu_news'] = $this->url->link('extension/news','type=10');
        $data['search'] = $this->url->link('product/search','', true);
        $data['text_menu_news'] = $this->language->get('text_menu_news');
        $data['news'] = $this->url->link('extension/news','type=1','title='.$this->language->get('text_news'));
        $data['text_news'] = $this->language->get('text_news');
        $data['shares'] = $this->url->link('extension/news','type=0','title='.$this->language->get('text_shares'));
        $data['text_shares'] = $this->language->get('text_shares');
        $data['developments'] = $this->url->link('extension/news','type=2');
        $data['text_developments'] = $this->language->get('text_developments');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_price_list'] = $this->language->get('text_price_list');

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

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {

            if ($category['top']) {
                // Level 2
                $children_data = array();

                $children = $this->model_catalog_category->getCategories($category['category_id']);

                foreach ($children as $child) {


                    $filter_data = array(
                        'filter_category_id' => $child['category_id'],
                        'filter_sub_category' => true
                    );

                    $children_data_l3 = array();

                    $children_l3 = $this->model_catalog_category->getCategories($child['category_id']);

                    foreach ($children_l3 as $child_l3) {

                        $children_data_l3[] = array(
                            //	'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts ($filter_data) . ')' : ''),
                            'name' => $child_l3['name'],
                            'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $child_l3['category_id'])
                        );

                    }


                        $children_data[] = array(
                            //	'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts ($filter_data) . ')' : ''),
                            'name' => $child['name'],
                            'children_l3' => $children_data_l3,
                            'column' => $child['column'] ? $child['column'] : 1,
                            'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
                        );
                    }

                    // Level 1
                    $data['categories'][] = array(
                        'name' => $category['name'],
                        'children' => $children_data,
                        'column' => $category['column'] ? $category['column'] : 1,
                        'href' => $this->url->link('product/category', 'path=' . $category['category_id'])
                    );
                }
            }

        $this->load->model('catalog/information');
        $data['informations'] = array();
        $informations = $this->model_catalog_information->getInformations();
            foreach ($informations as $information) {
                if ($information['menu']) {
                    // Level 2
                    $children_data_information = array();

                    $children_information = $this->model_catalog_information->getInformationLevel($information['information_id']);

               foreach ($children_information as $child_information) {

                      $children_data_information[] = array(
                            'name' => $child_information['title'],
                            'href' => $this->url->link('information/information', 'information_id=' . $child_information['information_id'])
                        );

                    }
                    $data['informations'][] = array(
                        'name' => $information['title'],
                        'children_information' => $children_data_information,
                        'column' => $information['column'] ? $information['column'] : 1,
                        'href' => $this->url->link('information/information', 'information_id=' . $information['information_id'])
                    );
                }
            }
		return $this->load->view('common/menu', $data);
	}
}
