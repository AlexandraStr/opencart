<?php

class ControllerToolSetCron extends Controller
{
    private $error = array();

    public function install() {
        $this->load->model('setting/event');
        $this->model_setting_event->addEvent('admin_tool_set_cron', 'admin/model/tool/set_cron/getTaskName/after', 'tool/set_cron/getTaskForm');
    }

    public function uninstall() {
        $this->load->model('setting/event');
        $this->model_setting_event->deleteEvent('admin_tool_set_cron');
    }


    public function index()
    {
        $this->load->language('tool/set_cron');
        $this->load->model('setting/event');
        $cron_events = array();
        $cron_events = $this->model_setting_event->getEventByCode('admin_tool_set_cron');


        if (!count($cron_events)) {
            $this->install();

        }
        $this->document->setTitle($this->language->get('heading_title'));

        if (!$this->user->hasPermission('modify', 'tool/set_cron')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }

        $this->getCronList();
    }

    public function getCronList(){


        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('tool/set_cron', 'user_token=' . $this->session->data['user_token'], true)
        );


        $data['button_submit'] = $this->language->get('button_submit');
     //   $data['button_send'] = $this->language->get('button_send');


        $data['user_token'] = $this->session->data['user_token'];
        $data['set_value'] = $this->url->link('tool/set_cron/setCron', 'user_token=' . $this->session->data['user_token'], true);
        $data['add'] = $this->url->link('tool/set_cron/addTask', 'user_token=' . $this->session->data['user_token'], true);
   //     $data['edit'] = $this->url->link('tool/set_cron/editTask', 'user_token=' . $this->session->data['user_token'], true);
   //     $data['delete'] = $this->url->link('tool/set_cron/deleteTask', 'user_token=' . $this->session->data['user_token'], true);

        $this->load->model('tool/set_cron');

        $results = $this->model_tool_set_cron->getCronValue();


        foreach ($results as $result) {
            $data['cron_values'][] = array(
                'task_id' => $result['task_id'],
                'task_name' => $result['task_name'],
                'task_title' => $result['task_title'],
                'day_begin' => $result['day_begin'],
                'day_end' => $result['day_end'],
                'time_begin' => $result['time_begin'],
                'time_end' => $result['time_end'],
                'dayofmonth' => $result['dayofmonth'],
                'month' => $result['month'],
                'interval' => $result['interval'],
                'edit' => $this->url->link('tool/set_cron/editTask', 'user_token=' . $this->session->data['user_token'] . '&task_id=' . $result['task_id'], true),
                'delete' => $this->url->link('tool/set_cron/deleteTask', 'user_token=' . $this->session->data['user_token'] . '&task_id=' . $result['task_id'], true)
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

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_task'] = $this->language->get('text_task');
        $data['text_days'] = $this->language->get('text_days');
        $data['text_begin_time'] = $this->language->get('text_begin_time');
        $data['text_end_time'] = $this->language->get('text_end_time');
        $data['text_interval'] = $this->language->get('text_interval');
        $data['text_monday'] = $this->language->get('text_monday');
        $data['text_tuesday'] = $this->language->get('text_tuesday');
        $data['text_wednesday'] = $this->language->get('text_wednesday');
        $data['text_thursday'] = $this->language->get('text_thursday');
        $data['text_friday'] = $this->language->get('text_friday');
        $data['text_saturday'] = $this->language->get('text_saturday');
        $data['text_sunday'] = $this->language->get('text_sunday');
        $data['button_save'] = $this->language->get('button_save');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('tool/set_cron_list', $data));
    }

    public function setCron()
    {
        $this->load->language('tool/set_cron');


        $filename= DIR_CSV.$this->config->get('synchron_name').".csv";
        $file_flag= DIR_CSV.$this->config->get('synchron_flag').".sng";
        $csv_delimiter=$this->config->get("csv_delimiter");
        $csv_header=$this->config->get("csv_header");
        $base_fields=$this->config->get("base_fields");



            $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('tool/set_cron');

        $cron_file=DIR_APPLICATION."crontab/crontab.txt";


        $new_cronjobs = array();

            if (isset($this->request->post['selected'])) {
                foreach ($this->request->post['selected'] as $task_id) {
                    $task_info = $this->model_tool_set_cron->getTaskId($task_id);
                    if($task_info['task_name']==="tool/import/synchron") {
                        $parameters = array(
                            'synchron_name' => $filename,
                            'synchron_flag' => $file_flag,
                            'csv_delimiter' => $csv_delimiter,
                            'csv_header' => $csv_header,
                            'base_fields'=> $base_fields,
                        );
                    } else {
                        $parameters = array("param" => true);
                    }
                    $new_cronjobs[] = array(
                        'key' => $task_info['task_name'],
                     //   'time'=> array( 'minute' => ($task_info['interval'] > 0) ? "*/" . $task_info['interval'] : "*",
                        'time'=> array( 'minute' => ($task_info['interval'] > 0) ? $task_info['interval'] : "*",
                        'hour' => ($task_info['time_begin'] <> $task_info['time_end']) ? $task_info['time_begin'] . "-" . $task_info['time_end'] : $task_info['time_begin'],
                        'day' => "*",
                        'dayofweek' => ($task_info['day_begin'] > 0 && $task_info['day_end'] > 0) ? $task_info['day_begin'] . "-" . $task_info['day_end'] : "*",
                        'dayofmonth' => $task_info['dayofmonth'],
                       ),
                            'parameters' => $parameters,

                    );
                }

                $dataCron = serialize($new_cronjobs);

                file_put_contents($cron_file, $dataCron);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('tool/set_cron', 'user_token=' . $this->session->data['user_token'], true));
        }else{
             $this->error['warning'] = $this->language->get('error_empty');
            }
        $this->getCronList();
    }

    public function addTask(){

            $this->load->language('tool/set_cron');

            $this->document->setTitle($this->language->get('heading_title'));

            $this->load->model('tool/set_cron');


            if ($this->request->server['REQUEST_METHOD'] == 'POST'&& $this->validateForm()) {
                $this->model_tool_set_cron->addTask($this->request->post);

                $this->session->data['success'] = $this->language->get('text_success');

                $this->response->redirect($this->url->link('tool/set_cron', 'user_token=' . $this->session->data['user_token'], true));
            }

        $this->getCronForm();
    }

    public function editTask() {
        $this->load->language('tool/set_cron');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('tool/set_cron');

        if ($this->request->server['REQUEST_METHOD'] == 'POST'&& $this->validateForm()) {
            $this->model_tool_set_cron->editTask($this->request->get['task_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');


            $this->response->redirect($this->url->link('tool/set_cron', 'user_token=' . $this->session->data['user_token'] , true));
        }

        $this->getCronForm();
    }

    public function deleteTask() {
        $this->load->language('tool/set_cron');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('tool/set_cron');

        if (isset($this->request->get['task_id'])) {

                $task_id=$this->request->get['task_id'];
                $this->model_tool_set_cron->deleteTask($task_id);


            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('tool/set_cron', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->getCronList();
    }

    protected function getCronForm() {
        $data['text_form'] = !isset($this->request->get['task_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/filter', 'user_token=' . $this->session->data['user_token'], true)
        );


        if (!isset($this->request->get['task_id'])) {
            $data['action'] = $this->url->link('tool/set_cron/addTask', 'user_token=' . $this->session->data['user_token'] , true);
        } else {
            $data['action'] = $this->url->link('tool/set_cron/editTask', 'user_token=' . $this->session->data['user_token'] . '&task_id=' . $this->request->get['task_id'] , true);
        }

        $data['cancel'] = $this->url->link('tool/set_cron', 'user_token=' . $this->session->data['user_token'], true);



        if (isset($this->request->get['task_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $task_info = $this->model_tool_set_cron->getTaskId($this->request->get['task_id']);
            $data['cron_values'] = array(
                'task_id' => $task_info['task_id'],
                'task_name' => $task_info['task_name'],
                'task_title' => $task_info['task_title'],
                'day_begin' => $task_info['day_begin'],
                'day_end' => $task_info['day_end'],
                'time_begin' => $task_info['time_begin'],
                'time_end' => $task_info['time_end'],
                'dayofmonth' => $task_info['dayofmonth'],
                'month' => $task_info['month'],
                'interval' => $task_info['interval'],
            );

        }

        $data['user_token'] = $this->session->data['user_token'];


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('tool/set_cron_form', $data));
    }


      public function getTaskForm(&$route, &$args, &$output) {
        $this->load->language('tool/set_cron');

          if (count($output)) {
              $data['text_form']= $this->language->get('text_edit');
              $data['cron_values'] = array(
                  'task_id' => $output['task_id'],
                  'task_name' => $output['task_name'],
                  'task_title' => $output['task_title'],
                  'day_begin' => $output['day_begin'],
                  'day_end' => $output['day_end'],
                  'time_begin' => $output['time_begin'],
                  'time_end' => $output['time_end'],
                  'dayofmonth' => $output['dayofmonth'],
                  'month' => $output['month'],
                  'interval' => $output['interval'],
              );
              $data['action'] = $this->url->link('tool/set_cron/editTask', 'user_token=' . $this->session->data['user_token'] . '&task_id=' . $output['task_id'] , true);
          } else {
              $data['text_form']= $this->language->get('text_add');
              $data['cron_values'] = array(
                  'task_name' => $args[0],
              );
              $data['action'] = $this->url->link('tool/set_cron/addTask', 'user_token=' . $this->session->data['user_token'] , true);
          }


          $data['cancel'] = $this->url->link('tool/set_cron', 'user_token=' . $this->session->data['user_token'], true);

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/filter', 'user_token=' . $this->session->data['user_token'], true)
        );


        $data['user_token'] = $this->session->data['user_token'];


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('tool/set_cron_form', $data));
    }



    protected function validateForm() {

        if ((utf8_strlen($this->request->post['task_name']) < 1) || (utf8_strlen($this->request->post['task_name']) > 50)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        return !$this->error;
    }
}