# directory-hash
A standard algorithm to compute the hash of a directory and all it's files.

## Example
```php
<?php

require 'vendor/autoload.php';

$dir = new \fizk\DirectoryHash\Standard();
echo $dir->hash();
```
