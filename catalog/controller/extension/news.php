<?php
class ControllerExtensionNews extends Controller {
	public function index() {
		$this->language->load('extension/news');
		
		$this->load->model('extension/news');
	 
		$this->document->setTitle($this->language->get('heading_title')); 
	 
		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
			'text' 		=> $this->language->get('text_home'),
			'href' 		=> $this->url->link('common/home')
		);
		
		$data['breadcrumbs'][] = array(
			'text' 		=> $this->language->get('heading_title'),
			'href' 		=> $this->url->link('extension/news')
		);
		  
		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}	

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else { 
			$page = 1;
		}

        if (isset($this->request->get['news_id']) && !empty($this->request->get['news_id'])) {
            $news_id = $this->request->get['news_id'];
        } else {
            $news_id = 0;
        }

        if (isset($this->request->get['type'])) {
            $type = (int)$this->request->get['type'];
        } else {
            $type = 10;
        }
		
		$filter_data = array(
			'type' 	=> $type,
			'limit' => 10,
			'start' => 10 * ($page - 1),
		);

        if ($type==10) {
            $total = $this->model_extension_news->getTotalNews();
        }else{
            $total = $this->model_extension_news->getTypeTotalNews($type);
        }
		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('extension/news', 'page={page}');
		
		$data['pagination'] = $pagination->render();
	 
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($total - 10)) ? $total : ((($page - 1) * 10) + 10), $total, ceil($total / 10));


		$data['text_title'] = $this->language->get('text_title');
		$data['text_date_public'] = $this->language->get('text_date_public');
        $data['text_type'] = $this->language->get('text_type');
		$data['text_description'] = $this->language->get('text_description');
		$data['text_date'] = $this->language->get('text_date');
		$data['text_view'] = $this->language->get('text_view');
        $data['text_action'] = $this->language->get('text_action');

        if ($type==10) {
            $all_news = $this->model_extension_news->getAllNews($filter_data);
            $data['heading_title'] = $this->language->get('heading_title');
        }else{
            $all_news = $this->model_extension_news->getTypeNews($filter_data);
            $data['heading_title'] = $this->model_extension_news->getTypeName($type);
        }

        $data['type'] = $type;

		$data['all_news'] = array();
		
		$this->load->model('tool/image');
	 
		foreach ($all_news as $news) {
			$data['all_news'][] = array (
			    'type_name'     => $news['type_name'],
				'title' 		=> html_entity_decode($news['title'], ENT_QUOTES),
				'image'			=> $this->model_tool_image->resize($news['image'], 100, 100),
				'short_description' 	=> (utf8_strlen(strip_tags(html_entity_decode($news['short_description'], ENT_QUOTES))) > 200 ? utf8_substr(strip_tags(html_entity_decode($news['short_description'], ENT_QUOTES)), 0, 200) . '...' : strip_tags(html_entity_decode($news['short_description'], ENT_QUOTES))),
            //    'short_description' 	=> html_entity_decode($news['short_description'], ENT_QUOTES),
                'view' 			=> $this->url->link('extension/news/news', 'news_id=' . $news['news_id']),
				'date_added' 	=> date($this->language->get('date_format_short'), strtotime($news['date_added']))
			);
		}
	 
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/news_list', $data));
	}
 
	public function news() {
		$this->load->model('extension/news');
	  
		$this->language->load('extension/news');
 
		if (isset($this->request->get['news_id']) && !empty($this->request->get['news_id'])) {
			$news_id = $this->request->get['news_id'];
		} else {
			$news_id = 0;
		}
 
		$news = $this->model_extension_news->getNews($news_id);
 
		$data['breadcrumbs'] = array();
	  
		$data['breadcrumbs'][] = array(
			'text' 			=> $this->language->get('text_home'),
			'href' 			=> $this->url->link('common/home')
		);
	  
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/news')
		);
 
		if ($news) {
			$data['breadcrumbs'][] = array(
				'text' 		=> $news['title'],
				'href' 		=> $this->url->link('extension/news/news', 'news_id=' . $news_id)
			);
 
			$this->document->setTitle($news['title']);
			
			$this->load->model('tool/image');
			
			$data['image'] = $this->model_tool_image->resize($news['image'], 200, 200);
 
			$data['heading_title'] = html_entity_decode($news['title'], ENT_QUOTES);
			$data['description'] = html_entity_decode($news['description'], ENT_QUOTES);
	 
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('extension/news', $data));
		} else {
			$data['breadcrumbs'][] = array(
				'text' 		=> $this->language->get('text_error'),
				'href' 		=> $this->url->link('extension/news/news', 'news_id=' . $news_id)
			);
	 
			$this->document->setTitle($this->language->get('text_error'));
	 
			$data['heading_title'] = $this->language->get('text_error');
			$data['text_error'] = $this->language->get('text_error');
			$data['button_continue'] = $this->language->get('button_continue');
			$data['continue'] = $this->url->link('common/home');
	 
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}
}