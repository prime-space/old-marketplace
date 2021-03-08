<?php

chdir(__DIR__);

include '../func/config.php';
include '../func/db.class.php';
include '../func/Sphinx.php';
include '../func/product.class.php';

$db = new db();

product::reindex();
