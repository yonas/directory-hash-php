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
    public function hash($options = array()) {
        $ignore_files = !empty($options['ignore_files']);

        $objects = new \RecursiveIteratorIterator(
            new $this->iterator_class($this->path),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        // Collect hashes of all files and directories.
        $hash_tree = array();
        foreach ($objects as $name => $object){
            if ($ignore_files && in_array($name, $options['ignore_files'])) {
                continue;
            }

            $hash_tree[$name] = $object;
        }

        // Sort, based on hash values.
        sort($hash_tree);

        // Compute final hash.
        $hash_string = $this->algo->hash(join('', $hash_tree));
        return $hash_string;
    }
}
