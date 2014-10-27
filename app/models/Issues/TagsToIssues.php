<?php
namespace Issues;

class TagsToIssues extends \Phalcon\Mvc\Model {

	public function initialize() {
		$this->belongsTo('issue_id', 'issues', 'id');
		$this->belongsTo('tag_id', 'tags', 'id');
	}	
}
