<?php

class TypesController extends \Framework\AbstractController {

	public function indexAction() {
		$this->view->setVar('types', (new Issues\Types())->get());
	}

}
