<?php
namespace fizk\DirectoryHash;

use fizk\DirectoryHash\Hash;

class FilePathHash implements Hash {
    private $algo;

    public function __construct($algo) {
        $this->algo = $algo;
    }

    public function hash($file_iterator) {
        // A file path hash consists of a hash of the file path and a hash of the file data.
        return $this->algo->hash($this->path_hash($file_iterator) . $this->file_hash($file_iterator));
    }

    /**
     * Hash the file path.
     * Directories are marked with a directory marker, so that the path hash will detect
     * the difference between files and directories of the same name.
     */
    protected function path_hash($file_iterator) {
        $path_hash = '';
        $path = $this->dir_parents($file_iterator->getSubPathname());
        $leaf = array_pop($path);

        foreach ($path as &$k) {
            $path_hash .= $this->algo->hash(self::DIRECTORY_MARKER() . $k);
        }

        if ($file_iterator->isDir()) {
            $leaf = self::DIRECTORY_MARKER() . $leaf;
        }

        $path_hash .= $this->algo->hash($leaf);

        return $path_hash;
    }

    /**
     * Get the standard non-modifiable directory marker. DO NOT CHANGE THIS.
     * This should be some combination of characters that should not appear in a file name.
     */
    protected static function DIRECTORY_MARKER() {
        return '/\\';
    }

    /**
     * Get a hash of the file data.
     */
    protected function file_hash($file_iterator) {
        return $this->algo->hash_file($file_iterator->getPathname());
    }

    /**
     * Get a file's parent directories, as well as the file itself.
     */
    protected function dir_parents($filepath) {
        $parents = explode(DIRECTORY_SEPARATOR, $filepath);
        return $parents;
    }
}
