<?php

require_once t3lib_extMgm::extPath('pt_mail').'res/class.tx_ptmail_address.php';

class tx_ptmail_address_testcase extends PHPUnit_Framework_TestCase {
	
	public function test_constructObjectWithTitleAndMailAddress() {
		$mailObj = new tx_ptmail_address('john@doe.org', 'John Doe');
		$this->assertEquals($mailObj->get_email(), 'john@doe.org', 'E-Mail address did not match');
		$this->assertEquals($mailObj->get_title(), 'John Doe', 'Title did not match');
	}
	
	public function test_constructObjectWithCondensedMailAddress() {
		$mailObj = new tx_ptmail_address('John Doe <john@doe.org>');
		$this->assertEquals($mailObj->get_email(), 'john@doe.org', 'E-Mail address did not match');
		$this->assertEquals($mailObj->get_title(), 'John Doe', 'Title did not match');
	}
	
	public function test_constructObjectWithEmailAddressOnly() {
		$mailObj = new tx_ptmail_address('john@doe.org');
		$this->assertEquals($mailObj->get_email(), 'john@doe.org', 'E-Mail address did not match');
		$this->assertEquals($mailObj->get_title(), '', 'Title not empty');
	}

	/**
	 * Fail on invalid email address
	 * 
	 * @expectedException tx_pttools_exceptionAssertion
	 */
	public function test_failOnInvalidEmailAddress() {
		$mailObj = new tx_ptmail_address('blubb');
	}
	
	
}

?>