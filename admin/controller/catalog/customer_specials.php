<?php
class ControllerCatalogCustomerSpecials extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('catalog/customer_specials');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/customer_specials');

        $this->getList();
    }


    public function edit()
    {
        $this->load->language('catalog/customer_specials');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/customer_specials');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->model_catalog_customer_specials->editCustomerSpecials($this->request->get['customer_group_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('catalog/customer_specials', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function repair() {
        $this->load->language('catalog/customer_specials');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/customer_specials');

        if ($this->validateRepair()) {
            $this->model_catalog_customer_specials->repairCustomerSpecials();

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('catalog/customer_specials', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getList();
    }

    protected function validateRepair() {
        if (!$this->user->hasPermission('modify', 'catalog/customer_specials')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function getList()
    {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/customer_specials', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['repair'] = $this->url->link('catalog/customer_specials/repair', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['customer_specials'] = array();

         $filter_data = array(
              'sort' => $sort,
              'order' => $order,
              'start' => ($page - 1) * $this->config->get('config_limit_admin'),
              'limit' => $this->config->get('config_limit_admin')
         );

        $customer_total = $this->model_catalog_customer_specials->getTotalCustomersGroups();

        $results = $this->model_catalog_customer_specials->getCustomersGroups($filter_data);

        foreach ($results as $result) {
            $data['customer_specials'][] = array(
                'customer_group_id' => $result['customer_group_id'],
                'name' => $result['name'],
                'sort_order' => $result['sort_order'],
                'percent'=>$result['percent'],    
                'edit' => $this->url->link('catalog/customer_specials/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_group_id=' . $result['customer_group_id'] . $url, true)
            );
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('catalog/customer_specials', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);
        $data['sort_sort_order'] = $this->url->link('catalog/customer_specials', 'user_token=' . $this->session->data['user_token'] . '&sort=sort_order' . $url, true);

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $customer_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('catalog/customer_specials', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/customer_specials_list', $data));
    }

    protected function getForm()
    {
        $data['text_form'] = !isset($this->request->get['customer_group_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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

        if (isset($this->error['keyword'])) {
            $data['error_keyword'] = $this->error['keyword'];
        } else {
            $data['error_keyword'] = '';
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/customer_specials', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        if (!isset($this->request->get['customer_group_id'])) {
            $data['action'] = $this->url->link('catalog/customer_specials/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        } else {
            $data['action'] = $this->url->link('catalog/customer_specials/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_group_id=' . $this->request->get['customer_group_id'] . $url, true);
        }

        $data['cancel'] = $this->url->link('catalog/customer_specials', 'user_token=' . $this->session->data['user_token'] . $url, true);

        if (isset($this->request->get['customer_group_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $customer_info = $this->model_catalog_customer_specials->getCustomerGroup($this->request->get['customer_group_id']);
        }

        $data['user_token'] = $this->session->data['user_token'];
//****************
     if (!empty($customer_info)) {
           $data['customer_group_id'] = $customer_info['customer_group_id'];
           $data['name'] = $customer_info['name'];
           $data['percent'] = $customer_info['percent'];
        } else {
            $data['name'] = '';
        }

       
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/customer_specials_form', $data));
    }

   
    

}

