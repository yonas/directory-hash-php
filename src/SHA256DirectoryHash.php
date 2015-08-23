<?php
namespace fizk\DirectoryHash;

use fizk\DirectoryHash\DirectoryHash;
use fizk\DirectoryHash\PHPHash;

class SHA256DirectoryHash extends DirectoryHash {
    public function __construct($path = '.') {
        parent::__construct($path, new PHPHash('sha256'), '\fizk\\DirectoryHash\\SHA256HashTreeRecursiveDirectoryIterator');
    }
}
