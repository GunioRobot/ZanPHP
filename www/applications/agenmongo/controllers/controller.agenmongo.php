<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

class Agenmongo_Controller extends ZP_Controller {
	
	public function __construct() {
		$this->application = $this->app("agenmongo");

		$this->Agenmongo_Model = $this->model("Agenmongo_Model");
				
		$this->Templates = $this->core("Templates");

		$this->Templates->theme(_webTheme);

		$this->RESTServer = $this->core("RESTServer");

		$this->RESTServer->application($this->application);
	}
	
	public function index() {		
		print __("Welcome to Agenda");
	}

	public function contacts() {
		$data = $this->Agenmongo_Model->getAllContacts();
		
		____($data);
	}

	public function add() {
		if($this->RESTServer->isREST(TRUE, "POST")) {
			$data = $this->Agenmongo_Model->add();
			
			if($data) {
				$message = $this->RESTServer->message("The contact has been inserted correctly");
			} else {
				$message = $this->RESTServer->message("Insert fail", TRUE);
			}
		} else {
			$message = $this->RESTServer->message("Invalid REST request", TRUE);
		}
		
		$this->RESTServer->response($message, FALSE, "POST");
	}

	public function contact($contactID) {
		$data = $this->Agenmongo_Model->getContact($contactID);

		if($data) {
			if($this->RESTServer->isREST(4)) {
				$this->RESTServer->process($data, 5);
			} else {
				$vars["contacts"] = $data;
				$vars["view"]	  = $this->view("contacts", TRUE);
				
				$this->template("content", $vars);
			}
		} else {
			$this->template("error404");	
		}
	}

	public function email($email) {
		$data = $this->Agenmongo_Model->getContactByEmail($email);

		____($data);
	}

	public function edit($contactID, $name, $email, $phone) {
		$data = array(
			"Name"  => $name,
			"Email" => $email,
			"Phone" => $phone
		);

		$response = $this->Agenmongo_Model->update($contactID, $data);

		____($response);
	}

	public function save($name, $email, $phone) {
		$this->Agenmongo_Model->save($name, $email, $phone);
	}

}