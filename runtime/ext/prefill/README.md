README
======

Copyright 2012, 2013 Defense I/O LLC
All rights reserved.

This extension can be used to natively prefill superglobals such as _GET, _POST, ... on a shell.

``bash
php -dprefill.get=id -dgprefill.get=test -dprefill.preload=foobar1 test_script.php
``

``php
<?php

$a = "Hello ";
$b = $_GET['test'] . " " . $_GET['id'];

echo( $a . $b ); // "Hello foobar1 foobar1"

?>
```

}


But.. why?
-----------------------------

This is for developers convienice during testing, but I primary use it for my source code
analysis work.

