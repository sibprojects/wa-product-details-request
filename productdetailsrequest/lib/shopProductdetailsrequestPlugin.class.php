<?php
class shopProductdetailsrequestPlugin extends shopPlugin{

	public function frontend_product($param){

		$view = wa()->getView();
		$view->assign('ProductDetailsRequestForm', $view->fetch($this->path.'/templates/form.html'));
	}

}
