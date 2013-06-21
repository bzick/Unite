<?php
namespace Unite\Test;
use RecursiveIterator;

/**
 * Test suit, folder of the tests
 */
class Suite extends \splFileInfo implements \RecursiveIterator {
    /**
     * @var \splFileInfo[]
     */
    public $files = array();

    public $started = false;
    /**
     * @var string path to the suite (unique)
     */
    public $path;
    /**
     * @var string suite name (not unique)
     */
    public $name;
    /**
     * @var \Unite
     */
    public $unite;
    public $load = false;
    /**
     * @var Suite
     */
    public $suite;
    public $cases = array();

    /**
     * @param string $path
     * @param Suite $suite
     */
    public function __construct($path, Suite $suite = null) {
        parent::__construct($path);
        $this->path = $this->getRealPath();
        $this->name = $this->getBasename();
        $this->suite = $suite;
    }

    public function __toString() {
        return "TestSuite({$this->name})";
    }

    /**
     * @param Suite $suite
     */
    public function setParentSuite(Suite $suite) {
        $this->suite = $suite;
    }

    public function before() {
        $this->started = true;
    }

    public function after() {

    }

    /**
     * Load files and directories
     */
    private function _load() {
        $files = iterator_to_array(new \FilesystemIterator($this->path, \FilesystemIterator::KEY_AS_FILENAME | \FilesystemIterator::CURRENT_AS_PATHNAME));
        /* @var \splFileInfo[] $files */
        if(isset($files[".order"])) {
            $order = file($this->path."/.order", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
            unset($files[".order"]);
            foreach($order as $item) {
                if(isset($files[$item])) {
                    if(is_dir($files[$item])) {
                        $this->files[$item] = new self($files[$item], $this);
                    } elseif(strrchr($files[$item], ".") === ".php") {
                        $this->files[$item] = new File($files[$item], $this);
                    }
                    unset($files[$item]);
                }
            }
        }
        if($files) {
            foreach($files as $item => $file) {
                if(is_dir($file)) {
                    $this->files[$item] = new self($file, $this);
                } elseif(strrchr($file, ".") === ".php") {
                    $this->files[$item] = new File($file, $this);
                }
            }
        }

    }

    /**
     * Mark directory as test suite
     * @param \Unite $unite
     */
    public function setTestSuite(\Unite $unite) {
        $this->unite = $unite;
    }
    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current() {
        return current($this->files);
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next() {
        next($this->files);
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key() {
        return current($this->files)->getPathname();
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid() {
        return current($this->files);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind() {
        if(!$this->load) {
            $this->_load();
            $this->load = true;
        }
        reset($this->files);
    }

    /**
     * Returns if an iterator can be created for the current entry.
     * @link http://php.net/manual/en/recursiveiterator.haschildren.php
     * @return bool true if the current entry can be iterated over, otherwise returns false.
     */
    public function hasChildren() {
        return current($this->files) instanceof self;
    }

    /**
     * Returns an iterator for the current entry.
     * @link http://php.net/manual/en/recursiveiterator.getchildren.php
     * @return RecursiveIterator An iterator for the current entry.
     */
    public function getChildren() {
        return current($this->files);
    }
}
