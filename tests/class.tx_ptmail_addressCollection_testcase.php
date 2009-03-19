<?php

require_once t3lib_extMgm::extPath('pt_mail').'res/class.tx_ptmail_addressCollection.php';

class tx_ptmail_addressCollection_testcase extends PHPUnit_Framework_TestCase {
	
	public function test_constructObjectWithAddressObjects() {
		
		$collectionObj = new tx_ptmail_addressCollection(new tx_ptmail_address('john@doe.org', 'John Doe'), new tx_ptmail_address('jane@doe.org', 'Jane Doe'));
		
		$this->assertEquals(count($collectionObj), 2, 'Collection did not contain 2 elements');
		$this->assertEquals($collectionObj->getItemByIndex(0)->get_email(), 'john@doe.org');
		$this->assertEquals($collectionObj->getItemByIndex(0)->get_title(), 'John Doe');		
		$this->assertEquals($collectionObj->getItemByIndex(1)->get_email(), 'jane@doe.org');
		$this->assertEquals($collectionObj->getItemByIndex(1)->get_title(), 'Jane Doe');
	}
	
	public function test_constructObjectWithCommaseparatedMailAddressesOnly() {
		
		$collectionObj = new tx_ptmail_addressCollection('john@doe.org,jane@doe.org');
		
		$this->assertEquals(count($collectionObj), 2, 'Collection did not contain 2 elements');
		$this->assertEquals($collectionObj->getItemByIndex(0)->get_email(), 'john@doe.org');
		$this->assertEquals($collectionObj->getItemByIndex(1)->get_email(), 'jane@doe.org');
	}
	
	public function test_constructObjectWithCommaseparatedCondensedMailAddresses() {
		
		$collectionObj = new tx_ptmail_addressCollection('John Doe <john@doe.org>, Jane Doe <jane@doe.org>');
		
		$this->assertEquals(count($collectionObj), 2, 'Collection did not contain 2 elements');
		$this->assertEquals($collectionObj->getItemByIndex(0)->get_email(), 'john@doe.org');
		$this->assertEquals($collectionObj->getItemByIndex(0)->get_title(), 'John Doe');		
		$this->assertEquals($collectionObj->getItemByIndex(1)->get_email(), 'jane@doe.org');
		$this->assertEquals($collectionObj->getItemByIndex(1)->get_title(), 'Jane Doe');
	}
	
	public function test_constructObjectWithMixedArguments() {
		$collectionObj = new tx_ptmail_addressCollection('John Doe <john@doe.org>', new tx_ptmail_address('jane@doe.org', 'Jane Doe'));
		
		$this->assertEquals(count($collectionObj), 2, 'Collection did not contain 2 elements');
		$this->assertEquals($collectionObj->getItemByIndex(0)->get_email(), 'john@doe.org');
		$this->assertEquals($collectionObj->getItemByIndex(0)->get_title(), 'John Doe');		
		$this->assertEquals($collectionObj->getItemByIndex(1)->get_email(), 'jane@doe.org');
		$this->assertEquals($collectionObj->getItemByIndex(1)->get_title(), 'Jane Doe');
	}
	
}

?>