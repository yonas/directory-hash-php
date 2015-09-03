# directory-hash
A standard algorithm to compute the hash of a directory, including all it's files and directory strucutre.

## Example
```php
<?php

require 'vendor/autoload.php';

$dir = new \fizk\DirectoryHash\Standard();
echo $dir->hash();
```
