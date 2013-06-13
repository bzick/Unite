<?php

namespace Unite\Test;


class RootSuite extends Suite {

	public $load = true;

	public function __construct(\Unite $unite) {
		$this->unite = $unite;
	}

	public function addSuite($suite) {
		$suite = new Suite($suite, $this);
		$this->files[$suite->getPathname()] = $suite;
	}

	public function addFile($file) {
		$file = new File($file, $this);
		$this->files[$file->getPathname()] = $file;
	}
}