<?php
// $Id: pager_sliding_noData_test.php,v 1.3 2007/10/16 20:39:20 quipo Exp $

require_once 'simple_include.php';
require_once 'pager_include.php';

class TestOfPagerSlidingNoData extends UnitTestCase {
    var $pager;
    function TestOfPagerSlidingNoData($name='Test of Pager_Sliding - no data') {
        $this->UnitTestCase($name);
    }
    function setUp() {
        $options = array(
            'totalItems' => 0,
            'perPage'  => 2,
            'mode'     => 'Sliding',
        );
        $this->pager = Pager::factory($options);
    }
    function tearDown() {
        unset($this->pager);
    }
    function testOffsetByPageId() {
        $this->assertEqual(array(1, 0), $this->pager->getOffsetByPageId());
    }
    function testPageIdByOffset() {
        $this->assertNull($this->pager->getPageIdByOffset(1));
    }
}
?>