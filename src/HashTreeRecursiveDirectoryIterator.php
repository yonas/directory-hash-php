<?php
namespace fizk\DirectoryHash;

abstract class HashTreeRecursiveDirectoryIterator extends \RecursiveDirectoryIterator {
    protected $algo;

    public function __construct($path, $flags = FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS) {
        parent::__construct($path, $flags);
    } 

    public function key() {
        // Remove "./" from beginning of path.
        // TODO: This might not be needed on Windows.
        return substr(parent::key(), 2);
    }

    public function current() {
        return $this->algo->hash(parent::key());
    }
}
