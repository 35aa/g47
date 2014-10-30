<?php

class ProjectsController extends \Framework\AbstractController {

	public function indexAction() {
		$this->view->setVar('projects', (new Issues\Projects())->get());
	}

}
