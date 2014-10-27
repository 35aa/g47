<?php

class IssuesController extends \Framework\AbstractController {

	public function indexAction() {
		$this->view->setVar('issues' => (new Issues())->getActiveIssues());
	}

}
