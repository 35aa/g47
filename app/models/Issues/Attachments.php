<?php
namespace Issues;

class Attachments extends \Phalcon\Mvc\Model {

	public function initialize() {
		$this->belongsTo('comment_id', 'comments', 'id');
	}	

	public function get() {
		return $this->find();
	}

	public function addFilter() {
		
	}

	public function search() {
		
	}
}
