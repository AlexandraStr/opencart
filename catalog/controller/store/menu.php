<?php
class ControllerStoreMenu extends Controller {
	public function index() {
		$this->load->language('store/menu');

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
                        'filter_category_id'  => $child['category_id'],
                        'filter_sub_category' => true
                    );

					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts ($filter_data) . ')' : ''),
			     //       'name'  => $child['name'],
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}

		return $this->load->view('common/menu', $data);
	}
}
