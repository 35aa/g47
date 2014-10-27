<?php
namespace Issues;

class Tags extends \Phalcon\Mvc\Model {
	protected _filters = array();

	public function initialize() {
		$this->belongsTo('projects_id', 'projects', 'id');
		$this->belongsTo('user_id', 'users', 'id');
		$this->hasMany('id', 'tags_to_issues', 'tag_id');
	}	

	public function get() {
		return $this->find();
	}

	public function getActive() {
		
	}

	public function addFilter() {
		
	}

	public function search() {
		
	}
}
