<?php

interface Hash {
    public function hash($data);
}

interface FileHash {
    public function hash_file($filepath);
}

class PHP_Hash implements Hash, FileHash {
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

class FilePathHash implements Hash {
    private $algo;

    public function __construct($algo) {
        $this->algo = $algo;
    }

    public function hash($filepath) {
        // A file path hash consists of a hash of the file path and a hash of the file data.
        return $this->algo->hash($this->path_hash($filepath) . $this->file_hash($filepath));
    }

    /**
     * Hash the file path.
     * Directories are marked with a directory marker, so that the path hash will detect
     * the difference between files and directories of the same name.
     */
    protected function path_hash($filepath) {
        $path_hash = '';
        $path = $this->dir_parents(substr($filepath, 2));
        $leaf = array_pop($path);

        foreach ($path as &$k) {
            $path_hash .= $this->algo->hash(self::DIRECTORY_MARKER() . $k);
        }

        if (is_dir($filepath))
            $leaf = self::DIRECTORY_MARKER() . $leaf;

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
    protected function file_hash($filepath) {
        return $this->algo->hash_file($filepath);
    }

    /**
     * Get a file's parent directories, as well as the file itself.
     */
    protected function dir_parents($filepath) {
        $parents = explode(DIRECTORY_SEPARATOR, $filepath);
        return $parents;
    }
}

abstract class HashTreeRecursiveDirectoryIterator extends RecursiveDirectoryIterator {
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

class SHA256_HashTreeRecursiveDirectoryIterator extends HashTreeRecursiveDirectoryIterator {
    public function __construct($path, $flags = FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS) {
        parent::__construct($path, $flags);
        $this->algo = new FilePathHash(new PHP_Hash('sha256'));
    } 
}

class DirectoryHash {
    protected $path;
    protected $algo;
    protected $iterator_class;

    public function __construct($path = '.', $algo, $iterator_class) {
        $this->path = $path;
        $this->algo = $algo;
        $this->iterator_class = $iterator_class;
    }

    /**
     * Compute the standard hash of a directory.
     * This includes all it's files and subdirectories, but not the current directory name.
     */
    public function hash() {
        $hash_tree = array();
        $objects = new RecursiveIteratorIterator(
            new $this->iterator_class($this->path),
            RecursiveIteratorIterator::SELF_FIRST
        );

        // Collect hashes of all files and directories.
        foreach ($objects as $name => $object){
            $hash_tree[$name] = $object;
        }

        // Sort, based on hash values.
        sort($hash_tree);

        // Compute final hash.
        $hash_string = $this->algo->hash(join('', $hash_tree));
        return $hash_string;
    }
}

class SHA256_DirectoryHash extends DirectoryHash {
    public function __construct($path = '.') {
        parent::__construct($path, new PHP_Hash('sha256'), 'SHA256_HashTreeRecursiveDirectoryIterator');
    }
}

class StandardDirectoryHash extends SHA256_DirectoryHash {
}
