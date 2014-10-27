<?php
namespace Issues;

class Comments extends \Phalcon\Mvc\Model {

	public function initialize() {
		$this->belongsTo('issue_id', 'issues', 'id');
		$this->hasMany('id', 'attachments', 'comment_id');
	}	

	public function get() {
		return $this->find();
	}

	public function addFilter() {
		
	}

	public function search() {
		
	}
}
