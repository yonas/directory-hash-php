<?php
namespace fizk\DirectoryHash;

use fizk\DirectoryHash\PHPHash;
use fizk\DirectoryHash\FilePathHash;
use fizk\DirectoryHash\HashTreeRecursiveDirectoryIterator;

class SHA256HashTreeRecursiveDirectoryIterator extends HashTreeRecursiveDirectoryIterator {
    public function __construct($path, $flags = \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::SKIP_DOTS) {
        parent::__construct($path, $flags);
        $this->algo = new FilePathHash(new PHPHash('sha256'));
    } 
}
