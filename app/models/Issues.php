<?php

class Users extends \Phalcon\Mvc\Model implements \Framework\Paginator\Adapter\DataProviderInterface {
	protected _filters = array();

	public function initialize() {
		$this->belongsTo('parent_id', 'issues', 'id');
		$this->belongsTo('projects_id', 'projects', 'id');
		$this->belongsTo('user_id', 'users', 'id');
		$this->belongsTo('type_id', 'types', 'id');
		$this->belongsTo('status_id', 'statuses', 'id');
		$this->belongsTo('priority_id', 'priorities', 'id');
		$this->hasMany('id', 'tags_to_issues', 'issue_id');
		$this->hasMany('id', 'comments', 'issue_id');
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

	public function setCurrentPage($page) {
	
	}

	public function getPaginate() {
		
	}
}
