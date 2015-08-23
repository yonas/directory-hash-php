<?php
namespace fizk\DirectoryHash;

use fizk\DirectoryHash\Hash;
use fizk\DirectoryHash\FileHash;
use fizk\DirectoryHash\PHPHash;
use fizk\DirectoryHash\FilePathHash;
use fizk\DirectoryHash\HashTreeRecursiveDirectoryIterator;
use fizk\DirectoryHash\SHA256HashTreeRecursiveDirectoryIterator;
use fizk\DirectoryHash\DirectoryHash;
use fizk\DirectoryHash\SHA256DirectoryHash;

class Standard extends SHA256DirectoryHash {
}
