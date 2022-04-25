<?php

class shopProductdetailsrequestPluginFrontendProductController extends waJsonController
{

    public function execute()
    {

			$email = waRequest::request('email', '', 'string');
			if($email=='' || !eregi("^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$", $email)){

        $this->response = array(
            'error' => 'email',
        );
				return;
			}			

			$question = waRequest::request('question', '', 'string');
			if($question==''){

        $this->response = array(
            'error' => 'question',
        );
				return;
			}			

			$_POST['captcha'] = waRequest::request('captcha', '', 'string');
			if (!wa()->getCaptcha()->isValid()) {

        $this->response = array(
            'error' => 'captcha',
        );
				return;
			}

			$productID = waRequest::request('productID', '', 'int');
			$product_model = new shopProductModel();
			$product = $product_model->getById($productID);

			// product url
			$params = array('product_url' => $product['url']);
			if ($product['category_id']) {
				$category_model = new shopCategoryModel();
				$c = $category_model->getById($product['category_id']);
				$params['category_url'] = $c['full_url'];
			}
			$url = wa()->getRouteUrl('shop/frontend/product', $params, 1);

			$app_settings_model = new waAppSettingsModel();
			$to_email = $app_settings_model->get(array('shop', 'productdetailsrequest'), 'EMAIL');

			$mail_message = new waMailMessage('Вопрос по '.$product['name'], 
																				'Посетитель задал вопрос по товару: <a href="'.$url.'">'.$product['name'].'</a><hr />'.$question);
			$mail_message->setFrom($email);
			$mail_message->setTo($to_email);
			$mail_message->send();

      $this->response = array(
          'error' => '',
          'email' => $email,
      );
    }

}

?>