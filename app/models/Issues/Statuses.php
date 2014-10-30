<?php
namespace Issues;

class Statuses extends \Phalcon\Mvc\Model implements \Framework\Paginator\Adapter\DataProviderInterface {
	protected $_filters = array();

	public function initialize() {
		$this->belongsTo('projects_id', 'projects', 'id');
		$this->belongsTo('user_id', 'users', 'id');
		$this->hasMany('id', 'issues', 'issue_id');
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

	public function getCount() {}

	public function getItemsWithOffsetAndLimit($offset = 0, $limit = 0) {}
}
