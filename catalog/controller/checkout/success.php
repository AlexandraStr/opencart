<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {
		$this->load->language('checkout/success');

     //   if (isset($this->session->data['order_id'])) {
	//		$this->cart->clear();

	//		unset($this->session->data['shipping_method']);
	//		unset($this->session->data['shipping_methods']);
	//		unset($this->session->data['payment_method']);
	//		unset($this->session->data['payment_methods']);
	//		unset($this->session->data['guest']);
	//		unset($this->session->data['comment']);
	//		unset($this->session->data['order_id']);
	//		unset($this->session->data['coupon']);
	//		unset($this->session->data['reward']);
	//		unset($this->session->data['voucher']);
	//		unset($this->session->data['vouchers']);
	//		unset($this->session->data['totals']);
	//	}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);
//**********************************************************************
            $data['order'] = array();
            $data['products'] = array();



        if (isset($this->session->data['order_id'])) {
            $order_id = $this->session->data['order_id'];

            $this->load->model('account/order');

            $order_info = $this->model_account_order->getOrder($order_id);

            if ($order_info) {
                $tax = 0;
                $shipping = 0;

                $order_totals = $this->model_account_order->getOrderTotals($order_id);

                foreach ($order_totals as $order_total) {
                    if ($order_total['code'] == 'tax') {
                        $tax += $order_total['value'];
                    } elseif ($order_total['code'] == 'shipping') {
                        $shipping += $order_total['value'];
                    }
                }


//запрос данных о заказе
                $data['order'] = $order_info;
                $data['order']['store_name'] = $this->config->get('config_name');
                $data['order']['order_total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
                $data['order']['order_tax'] = $this->currency->format($tax, $order_info['currency_code'], $order_info['currency_value'], false);
                $data['order']['order_shipping'] = $this->currency->format($shipping, $order_info['currency_code'], $order_info['currency_value'], false);

// запрос данных о товарах в заказе
                $products = $this->model_account_order->getOrderProducts($order_id);

                $this->load->model('catalog/product');
                $this->load->model('catalog/category');

                foreach ($products as $product) {
                    $sku = '';

                    $product_info = $this->model_catalog_product->getProduct($product['product_id']);

                    if ($product_info) {
                        $sku = $product_info['sku'];
                    }

                    $categories = array();

                    $product_categories = $this->model_catalog_product->getCategories($product['product_id']);

                    if ($product_categories) {
                        foreach ($product_categories as $product_category) {
                            $category_data = $this->model_catalog_category->getCategory($product_category['category_id']);

                            if ($category_data) {
                                $categories[] = $category_data['name'];
                            }
                        }
                    }
                
                    
                    $data['products'][] = array(
                        'order_id' => $order_id,
                        'product_id' => $product['product_id'],
                        'model' => $product['model'],
                        'name' => $product['name'],
                        'category' => implode(',', $categories),
                        'quantity' => $product['quantity'],
                        'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value'], false)
                      
                    );
                }
            }

            $this->cart->clear();

           	unset($this->session->data['shipping_method']);
         	unset($this->session->data['shipping_methods']);
         	unset($this->session->data['payment_method']);
          	unset($this->session->data['payment_methods']);
        	unset($this->session->data['guest']);
           	unset($this->session->data['comment']);
          	unset($this->session->data['order_id']);
         	unset($this->session->data['coupon']);
           	unset($this->session->data['reward']);
           	unset($this->session->data['voucher']);
            unset($this->session->data['vouchers']);
         	unset($this->session->data['totals']);

        }

 //*********************************************************************************

        if ($this->customer->isLogged()) {
			$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));
	}
}