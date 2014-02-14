<?php

class UserToRememberMe extends \Phalcon\Mvc\Model {

	public function create($data = array(), $whiteList = array()) {
		if (count($data)) $this->assign($data);
			$this->generateToken();
			$this->last_updated = time();
			$this->save();
			return $this;
	}

	public function getCodeByUserID($user_id) {
		return self::findFirst(array('user_id = :user_id:', 'bind' => array('user_id' => $user_id)));
	}

	public function getCodeByCode($code) {
		return self::findFirst(array('code = :code:', 'bind' => array('code' => $code)));
	}

	public function renewCode() {
		$this->generateToken();
		$this->last_updated = time();
		$this->save();
		return $this;
	}

	public function generateToken() {
		return $this->code = md5(microtime(true));
	}

}
