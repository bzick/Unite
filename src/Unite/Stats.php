<?php

namespace Unite;


class Stats {
    public $parent;
    public $start_time = 0.0;
    public $total_time = 0.0;
    public $start_memory = 0.0;
    public $total_memory = 0.0;
    public $asserts = 0;
    public $tests_count   = 0;
    public $tests_success = 0;
    public $tests_filed   = 0;
    public $tests_skipped = 0;

    public function __construct($parent, $tests_count) {
        $this->parent = $parent;
        $this->tests_count = $tests_count;
    }
}