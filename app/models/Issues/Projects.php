<?php
namespace Issues;

class Projects extends \Phalcon\Mvc\Model implements \Framework\Paginator\Adapter\DataProviderInterface {
	protected _filters = array();

	public function initialize() {
		$this->belongsTo('user_id', 'users', 'id');
		$this->hasMany('id', 'issues', 'project_id');
		$this->hasMany('id', 'priorities', 'project_id');
		$this->hasMany('id', 'types', 'project_id');
		$this->hasMany('id', 'statuses', 'project_id');
		$this->hasMany('id', 'tags', 'project_id');
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
