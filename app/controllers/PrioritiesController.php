<?php

class PrioritiesController extends \Framework\AbstractController {

	public function indexAction() {
		$this->view->setVar('priorities', (new Issues\Priorities())->get());
	}

}
