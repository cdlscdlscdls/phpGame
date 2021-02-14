<?php
require('./Model/Action.php');

print("test \n");

$test = 'waa';

print("test ${test}\n");

$action = new Action("A",0,1,1,"溜め");
$name = $action->getName();
print("test ${name}\n");
