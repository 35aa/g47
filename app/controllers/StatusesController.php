<?php

class StatusesController extends \Framework\AbstractController {

	public function indexAction() {
		$this->view->setVar('statuses', (new Issues\Statuses())->get());
	}

}
