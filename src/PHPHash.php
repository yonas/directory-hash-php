<?php
namespace fizk\DirectoryHash;

use fizk\DirectoryHash\Hash;
use fizk\DirectoryHash\FileHash;

class PHPHash implements Hash, FileHash {
    protected $algo;

    public function __construct($algo) {
        $this->algo = $algo;
    }

    public function hash($data) {
        return hash($this->algo, $data);
    }

    public function hash_file($filename) {
        return hash_file($this->algo, $filename);
    }
}
