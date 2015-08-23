<?php
namespace fizk\DirectoryHash;

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
        $objects = new \RecursiveIteratorIterator(
            new $this->iterator_class($this->path),
            \RecursiveIteratorIterator::SELF_FIRST
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
