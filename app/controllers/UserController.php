<?php

class UserController extends \Phalcon\Mvc\Controller {

	public function initialize() {
		if ($this->session->get('auth')->isAuthenticated()) {$this->response->redirect("home/index");}//redirect to index/index page
	}

	public function indexAction() {}
	
	public function signupAction() {
		if (!$this->view->form) $this->view->setVar('form', new UserForm\SignupForm());
		if (!$this->view->captcha) $this->view->setVar('captcha', new Captcha\Captcha($this->getDI()->get('config')->recaptcha));
	}

	public function registerAction() {
		$emailRegistered = false;
		$captcha = null;
		$form = null;
		if ($this->getDI()->getRequest()->isPost()) {
			$form = new UserForm\SignupForm();
			$captcha = new Captcha\Captcha($this->getDI()->get('config')->recaptcha);
			$newUser = new Users();

			if ($form->isValid($this->getDI()->getRequest()->getPost(), $newUser)
					&& $captcha->checkAnswer($this->getDI()->getRequest())
					// TODO: rename isEmailRegistered to getRegisteredEmail
					&& !($emailRegistered = UsersEmails::isEmailRegistered($newUser->email))) {
				// create new user
				$newUser->create();
				// send verification data to primary email
				$newUser->getPrimaryEmail()->sendVerifyEmail($this->getDI()->get('config'));

				// redirect to sing up confirmation page
				return $this->view->pick('user/signup_confirmation');
			}

			else if ($emailRegistered) {
				$this->view->setVar('emailRegistered', true);
			}
		}

		if ($captcha && $form) {
			$this->view->setVars(array('captcha' => $captcha, 'form' => $form));
			$this->view->form->get('password')->clear();
			$this->view->form->get('confirmPassword')->clear();
		}

		$this->dispatcher->forward(array(
				"controller" => "user",
				"action" => "signup" ));

	}

	public function signinAction() {
		if (!$this->view->form) $this->view->setVar('form', new UserForm\SigninForm());
		if ($this->session->get('auth')->isMaxRetryCount()
				&& !$this->view->captcha) {
			$this->view->setVar('captcha', new Captcha\Captcha($this->getDI()->get('config')->recaptcha));
		}
	}

	public function checkCredentialsAction() {
		$captcha = null;
		$form = null;
		if ($this->getDI()->getRequest()->isPost()) {
			$form = new UserForm\SigninForm();
			$checkCaptcha = true;
			if ($this->session->get('auth')->isMaxRetryCount()) {
				$captcha = new Captcha\Captcha($this->getDI()->get('config')->recaptcha);
				$checkCaptcha = $captcha->checkAnswer($this->getDI()->getRequest());
			}
			$usersTable = new Users();
			$validatedData = (Object) Array();
			if ($form->isValid($this->getDI()->getRequest()->getPost(), $validatedData)
					&& $checkCaptcha
					&& $user = $usersTable->getUserByPrimaryEmailAndPass($validatedData->email, $validatedData->password)) {

				$this->session->set('auth', new \Auth($user));
				$this->response->redirect("home/index");
				return;
			}
		}

		if ($form) {
			$this->session->get('auth')->incrementRetryCount();
			$this->view->setVars(array('captcha' => $captcha, 'form' => $form));
			$this->view->form->get('password')->clear();
		}

		$this->dispatcher->forward(array(
				"controller" => "user",
				"action" => "signin" ));

	}

	public function forgotpasswordAction() {
		if (!$this->view->form) $this->view->setVar('form', new UserForm\ForgotPassword());
		if (!$this->view->captcha) {
			$this->view->setVar('captcha', new Captcha\Captcha($this->getDI()->get('config')->recaptcha));
		}
	}

	public function sendresetpasswordAction() {
		$captcha = null;
		$form = null;
		$emailSent = false;
		if ($this->getDI()->getRequest()->isPost()) {
			$form = new UserForm\ForgotPassword();
			$captcha = new Captcha\Captcha($this->getDI()->get('config')->recaptcha);

			$usersTable = new Users();
			$validatedData = (Object) Array();
			if ($form->isValid($this->getDI()->getRequest()->getPost(), $validatedData)
					&& $captcha->checkAnswer($this->getDI()->getRequest())
					&& $user = $usersTable->getUserByPrimaryEmail($validatedData->email)) {

				$emailSent = true;
				$user->getPrimaryEmail()->sendResetPasswordEmail($this->getDI()->get('config'));
				return $this->view->pick('user/reset_confirmation');
			}
		}

		if ($form) {
			$this->view->setVars(array('captcha' => $captcha, 'form' => $form));
		}

		if (!$emailSent) {
			$this->dispatcher->forward(array(
					"controller" => "user",
					"action" => "forgotpassword" ));
		}

	}

	public function resetpasswordAction() {
		if (!$this->session->get('reset-auth')) {
			$this->dispatcher->forward(array(
					"controller" => "user",
					"action" => "signin" ));
		}
		if (!$this->view->form) $this->view->setVar('form', new UserForm\ResetPassword());
		if (!$this->view->captcha) {
			$this->view->setVar('captcha', new Captcha\Captcha($this->getDI()->get('config')->recaptcha));
		}
		$this->view->form->get('password')->clear();
		$this->view->form->get('confirmPassword')->clear();
	}

	public function setnewpasswordAction() {
		if (!$this->session->get('reset-auth')) {
			$this->dispatcher->forward(array(
					"controller" => "user",
					"action" => "signin" ));
		}
		$captcha = null;
		$form = null;
		$user = null;
		if ($this->getDI()->getRequest()->isPost()) {
			$form = new UserForm\ResetPassword();
			$captcha = new Captcha\Captcha($this->getDI()->get('config')->recaptcha);

			$usersTable = new Users();
			$validatedData = (Object) Array();
			if ($form->isValid($this->getDI()->getRequest()->getPost(), $validatedData)
					&& $captcha->checkAnswer($this->getDI()->getRequest())
					&& $user = $usersTable->getUserById($this->session->get('reset-auth')->getUserId())) {

				$user->hashPassword($validatedData->password);
				$user->save();
				$this->session->remove('reset-auth');
				$this->session->set('auth', new \Auth($user));
				return $this->dispatcher->forward(array(
							"controller" => "home",
							"action" => "index" ));
				}
		}

		if ($form) {
			$this->view->setVars(array('captcha' => $captcha, 'form' => $form));
		}

		$this->dispatcher->forward(array(
				"controller" => "user",
				"action" => "resetpassword" ));

	}

	public function signoutAction() {
		//Destroy the whole session
		$this->session->destroy();
		return $this->response->redirect('index/index');
	}

}
