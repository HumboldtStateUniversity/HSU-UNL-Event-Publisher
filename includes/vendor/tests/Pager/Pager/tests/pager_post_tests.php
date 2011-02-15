<?php
// $Id: pager_post_tests.php,v 1.2 2008/03/26 22:18:31 quipo Exp $

require_once 'simple_include.php';
require_once 'pager_include.php';

$test = &new GroupTest('Pager POST tests');
$test->addTestFile('pager_post_test.php');
$test->addTestFile('pager_post_test_simple.php');
exit ($test->run(new HTMLReporter()) ? 0 : 1);

?>