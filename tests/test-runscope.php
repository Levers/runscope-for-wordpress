<?php

class RunscopeTest extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();

		$this->plugin = $GLOBALS['runscope'];
	}

	public function testTrue() {
		$this->assertTrue( true );
	}

}