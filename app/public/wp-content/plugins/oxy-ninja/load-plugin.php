<?php

/* Include all of our Oxygen Elements dynamically */
$elements_filenames = glob(plugin_dir_path(__FILE__)."elements/*.php");
foreach ($elements_filenames as $filename) {
    include_once $filename;
}