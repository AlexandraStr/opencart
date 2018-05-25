<?php
require_once DIR_SYSTEM . 'library/crontab_manager.php';

class ControllerToolImport extends Controller
{
    public function index()
    {
        $this->load->language('tool/import');
        $this->document->setTitle($this->language->get('heading_title'));

        $data['entry_synchron_name ']=$this->language->get('entry_synchron_name ');
        $data['entry_synchron_flag ']=$this->language->get('entry_synchron_flag ');

        if (isset($this->request->post['synchron_name'])) {
            $data['synchron_name'] = $this->request->post['synchron_name'];
        } else {
            $data['synchron_name'] = $this->config->get('synchron_name');
        }

        if (isset($this->request->post['synchron_flag'])) {
            $data['synchron_flag'] = $this->request->post['synchron_flag'];
        } else {
            $data['synchron_flag'] = $this->config->get('synchron_flag');
        }

        if (isset($this->request->post['csv_delimiter'])) {
            $data['csv_delimiter'] = $this->request->post['csv_delimiter'];
        } else {
            $data['csv_delimiter'] = $this->config->get('csv_delimiter');
        }
        if (isset($this->request->post['csv_header'])) {
            $data['csv_header'] = $this->request->post['csv_header'];
        } else {
            $data['csv_header'] = $this->config->get('csv_header');
        }

        if (isset($this->request->post['base_fields'])) {
            $data['base_fields'] = $this->request->post['base_fields'];
        } else {
            $data['base_fields'] = implode(",",$this->config->get('base_fields'));
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

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('tool/import', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['user_token'] = $this->session->data['user_token'];
        $data['export'] = $this->url->link('tool/import/export', 'user_token=' . $this->session->data['user_token'], true);
        $data['save_name_synchron'] = $this->url->link('tool/import/save_name_synchron', 'user_token=' . $this->session->data['user_token'], true);

        $this->load->model('tool/import');

        $data['table_dates'] = $this->model_tool_import->showOrders();

        $this->load->model('setting/setting');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('tool/import', $data));
    }

    public function import()
    {
        $this->load->language('tool/import');
        $this->load->model('tool/import');
        $this->load->model('catalog/customer_specials');

        $json = array();

        if (isset($this->request->files['import']['tmp_name']) && is_uploaded_file($this->request->files['import']['tmp_name'])) {
            $filename = tempnam(DIR_UPLOAD, 'csv');
            move_uploaded_file($this->request->files['import']['tmp_name'], $filename);
        } elseif (isset($this->request->get['import'])) {
            $filename = html_entity_decode($this->request->get['import'], ENT_QUOTES, 'UTF-8');
        } else {
            $filename = '';
        }

        if (!is_file($filename)) {
            $json['error'] = $this->language->get('error_file');
        }

        if (isset($this->request->get['position'])) {
            $position = $this->request->get['position'];
        } else {
            $position = 0;
        }

        if (!$json) {
            // We set $i so we can batch execute the queries rather than do them all at once.
            // Declare variables
            $small_date = date('Y-m-d');
            $big_date = date('Y-m-d H:i:s');
            $image_category = " ";
            $image_manufacturer = " ";
            $image_product = " ";
            // SEO escape table
            $escape_table = array(
                'а' => 'a',
                'б' => 'b',
                'в' => 'v',
                'г' => 'g',
                'д' => 'd',
                'е' => 'e',
                'ё' => 'e',
                'ж' => 'zh',
                'з' => 'z',
                'и' => 'i',
                'й' => 'j',
                'к' => 'k',
                'л' => 'l',
                'м' => 'm',
                'н' => 'n',
                'о' => 'o',
                'п' => 'p',
                'р' => 'r',
                'с' => 's',
                'т' => 't',
                'у' => 'u',
                'ф' => 'f',
                'х' => 'h',
                'ц' => 'c',
                'ч' => 'ch',
                'ш' => 'sh',
                'щ' => 'sch',
                'ъ' => '',
                'ы' => 'y',
                'ь' => '',
                'э' => 'e',
                'ю' => 'yu',
                'я' => 'ya',
                'і' => 'i',
                'ї' => 'i',
                ' ' => '-',
            );


// Open the file csv
            $handle = fopen($filename, "r");

            if ($handle) {

                while (($row = fgetcsv($handle, 1024, ";")) !== FALSE) {
                    $model = addslashes(trim($row[0]));
                    if (strtoupper($model) == "KOD") {
                        continue;
                    }
                    $model = mb_convert_encoding($model, 'utf-8', 'windows-1251');
                    $name = addslashes(trim($row[1]));
                    $name = mb_convert_encoding($name, 'utf-8', 'windows-1251');
                    $sbrend = addslashes(trim($row[2]));
                    $sbrend = mb_convert_encoding($sbrend, 'utf-8', 'windows-1251');
                    $categ = addslashes(trim($row[3]));;
                    $categ = mb_convert_encoding($categ, 'utf-8', 'windows-1251');
                    $quant = trim($row[4]);
                    $price = trim($row[5]);

                    $result = $this->model_tool_import->getManufacturer($sbrend);

                    if (empty($result)) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "`manufacturer` (`name`,`image`) VALUES ('$sbrend','$image_manufacturer')");
                        $manufacturer_id = $this->db->getLastId();
                        $this->db->query("INSERT INTO " . DB_PREFIX . " `manufacturer_to_store` (manufacturer_id, store_id) VALUES ('$manufacturer_id','0')");

                        for ($lg = 1; $lg <= 2; $lg++) {
                            $seo_url = strtr(mb_strtolower($sbrend), $escape_table) . $manufacturer_id . $lg;
                            $seo_query = "manufacturer_id =" . $manufacturer_id;
                            $this->db->query("INSERT INTO `seo_url` (store_id,language_id,query,keyword)  VALUES ('0','$lg','$seo_query','$seo_url')");
                        }

                    } else {
                        $manufacturer_id = $result[0]['manufacturer_id'];
                    }

                    //Category

                    $result = $this->model_tool_import->getCategory($categ);

                    if (empty($result) AND !empty($categ)) {
                        $this->db->query("INSERT INTO `category` (image,top,parent_id,status,date_added,date_modified) VALUES ('$image_category','1','0','1','$big_date','$big_date')");
                        $category_id = $this->db->getLastId();

                        $this->db->query("INSERT INTO `category_path` (category_id, path_id, level) VALUES ('$category_id','$category_id','0')");

                        $this->db->query("INSERT INTO `category_to_store` (category_id, store_id)  VALUES ('$category_id','0')");

                        for ($lang = 1; $lang <= 2; $lang++) {
                            $this->db->query("INSERT INTO `category_description` (category_id, language_id, name, meta_title) VALUES('$category_id','$lang','$categ','$categ') ");
                        }

                        for ($lg = 1; $lg <= 2; $lg++) {
                            $seo_url = strtr(mb_strtolower($categ), $escape_table) . $category_id . $lg;
                            $seo_query = "category_id =" . $category_id;
                            $this->db->query("INSERT INTO `seo_url` (store_id,language_id,query,keyword) VALUES ('0','$lg','$seo_query','$seo_url')");
                        }
                    } elseif (!empty($categ)) {
                        $category_id = $result[0]['category_id'];
                    } else {
                        $category_id=0;
                    }


//Product

                    $result = $this->model_tool_import->getProduct($model);
                    $stock_status_id = ($quant > 0) ? 7 : 5;

                    if (empty($result)) {

                        $this->db->query("INSERT INTO `product` (model, quantity, stock_status_id, manufacturer_id, shipping, price, status,date_available,date_added,date_modified)
            VALUES('$model','$quant','$stock_status_id','$manufacturer_id','1','$price','1','$small_date','$big_date','$big_date') ");
                        $product_id = $this->db->getLastId();

                        for ($lang = 1; $lang <= 2; $lang++) {
                            $this->db->query("INSERT INTO `product_description` (product_id, language_id, name,meta_title)"
                                . " VALUES ('$product_id','$lang','$name','$name') ");
                        }
                        $this->db->query("INSERT INTO `product_to_category`( product_id,category_id) VALUES ('$product_id','$category_id')");

                        $this->db->query("INSERT INTO `product_to_store`(product_id, store_id) VALUES ('$product_id','0')");

                        for ($lg = 1; $lg <= 2; $lg++) {
                            $seo_url = strtr(mb_strtolower($name), $escape_table) . $product_id . $lg;
                            $seo_query = "product_id=" . (int)$product_id;
                            $this->db->query("INSERT INTO `seo_url` (store_id,language_id,query,keyword)
                          VALUES ('0','$lg','$seo_query','$seo_url')");
                        }


                    } else {
                        $product_id = $result[0]['product_id'];
                        $this->db->query("UPDATE `product` 
                SET model = '$model', quantity = '$quant', stock_status_id = '$stock_status_id',
                manufacturer_id = '$manufacturer_id' , shipping = '1', price = '$price', status = '1' ,
                date_available = '$small_date' ,date_added = '$big_date' ,date_modified = '$big_date'
                WHERE product_id = '$product_id'");

                        $this->db->query("UPDATE `product_to_category` SET category_id = '$category_id' 
                                WHERE product_id = '$product_id' ");

                        $this->db->query("UPDATE `product_to_store` SET store_id = '0'
                             WHERE product_id = '$product_id' ");

                    }
                }
                $this->model_catalog_customer_specials->repairCustomerSpecials();

            } else {
                echo "Неможливо прочитати файл " . $filename;
            }


            $position = ftell($handle);

            $size = filesize($filename);

            $json['total'] = round(($position / $size) * 100);

            if ($position && !feof($handle)) {
                $json['next'] = str_replace('&amp;', '&', $this->url->link('tool/import/import', 'user_token=' . $this->session->data['user_token'] . '&import=' . $filename . '&position=' . $position, true));

                fclose($handle);
            } else {
                fclose($handle);

                unlink($filename);

                $json['success'] = $this->language->get('text_success');

                $this->cache->delete('*');
            }

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }


    }

    public function export()
    {

        $this->load->language('tool/import');

        if (!isset($this->request->post['export'])) {
            $this->session->data['error'] = $this->language->get('error_export');

            $this->response->redirect($this->url->link('tool/import', 'user_token=' . $this->session->data['user_token'], true));
        } elseif (!$this->user->hasPermission('modify', 'tool/import')) {
            $this->session->data['error'] = $this->language->get('error_permission');

            $this->response->redirect($this->url->link('tool/import', 'user_token=' . $this->session->data['user_token'], true));
        } else {

            $this->response->addheader('Pragma: public');
            $this->response->addheader('Expires: 0');
            $this->response->addheader('Content-Description: File Transfer');
            $this->response->addheader('Content-Type: application/force-download');
            $this->response->addheader('Content-Disposition: attachment; filename="output_' . date('Y-m-d_H-i-s', time()) . '_order.csv"');
            $this->response->addheader('Content-Transfer-Encoding: binary');

            $this->load->model('tool/import');

            $this->response->setOutput($this->model_tool_import->exportOrders($this->request->post['export']));
        }
    }

    public function export_cron()
    {
        $datenow = date("Y-m-d");

        $this->load->model('tool/import');

        $this->load->model('io/file/writter');
        /** @var ModelIoFileWritter $exportWritter */
        $exportWritter = $this->registry->get('model_io_file_writter');

        $this->load->model('io/file/logger');
        /** @var ModelIoFileLogger $logWritter */
        $logWritter = $this->registry->get('model_io_file_logger')->getInstance();

        $logWritter->setLogFileName(DIR_EXPORT. "logfile.txt");


        $logWritter->write('Export has been started...');

        $output = $this->model_tool_import->exportOrdersForDate();

        if (!empty($output)) {

            $exportWritter->open(DIR_EXPORT . 'order_' . $datenow . '.csv')
                ->write($output)
                ->close();

            $logWritter->write('Export completed ');
        } else {
            $logWritter->write('No data to export ');
        }

    }

    public function save_name_synchron()
    {
        $this->load->language('tool/import');
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_setting_setting->editSettingValue('tool_import','synchron_name', $this->request->post['synchron_name']);
            $this->model_setting_setting->editSettingValue('tool_import','synchron_flag', $this->request->post['synchron_flag']);
            $this->model_setting_setting->editSettingValue('tool_import','csv_delimiter', $this->request->post['csv_delimiter']);
            $on_header=(int)isset($this->request->post['csv_header']);
            $this->model_setting_setting->editSettingValue('tool_import','csv_header',$on_header);
            $val=explode(",",$this->request->post['base_fields']);
            $this->model_setting_setting->editSettingValue('tool_import','base_fields',  $val);

            $this->session->data['success'] = $this->language->get('text_success');
        }

        $this->response->redirect($this->url->link('tool/import', 'user_token=' . $this->session->data['user_token'], true));
    }

    public function synchron($parameters)
    {


        $filename=$parameters['synchron_name'];
        $file_flag=$parameters['synchron_flag'];
        $header = $parameters['csv_header'];
        $delim = $parameters['csv_delimiter'];
        $fields = $parameters['base_fields'];
        $date_today = date("m.d.y");
        $time = date("H:i:s");
        $file_log = DIR_CSV . "logfile.txt";

        if (!file_exists($file_flag)) { return false; }
        if (!file_exists($filename)) { return false; }

        if (file_exists($file_log)) {
            unlink($file_log);
        }

            $this->load->language('tool/import');
            $this->load->model('tool/import');

            $cont = trim( file_get_contents( $filename ) );
            $encoded_cont = mb_convert_encoding( $cont, 'UTF-8', 'windows-1251');

            $row_delimiter = "";

        // определим разделитель
          if( !$row_delimiter ){
            $row_delimiter = "\r\n";
            if( false === strpos($encoded_cont, "\r\n") )
                $row_delimiter = "\n";
         }

        $lines = explode( $row_delimiter, trim($encoded_cont) );
        $lines = array_filter( $lines );
        $lines = array_map( 'trim', $lines );

        if($header) {
            array_shift($lines);
        }


        $data = [];
        foreach( $lines as $line ){
            $data[] = str_getcsv( $line, $delim ); // linedata
        }
        $fp = fopen($file_log, "w"); // ("r" - считывать "w" - создавать "a" - добовлять к тексту),мы создаем файл


        $str_date = count($data,COUNT_RECURSIVE)/count($data)-1;

        foreach ($data as $value) {
            for ($i = 0; $i < $str_date; $i++) {
                $setdate[$i] = $fields[$i]."='".$value[$i]."'";
            }
            $setstr=implode(",",$setdate);
            $wherestr=$value[0];
            fwrite($fp, "Записуємо дані " . $setstr  );
            $this->model_tool_import->UpdateCsv($setstr,$wherestr);
        }

        fwrite($fp, "Синхрогізація відбулася " . $date_today . " в " . $time);
        fclose($fp);
        unlink($file_flag);
        unlink($filename);


    }


    private function unicode_escape($str)
    {
        global $escape_table;
        return strtr($str, $escape_table);
    }


    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'tool/import')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

    }
}