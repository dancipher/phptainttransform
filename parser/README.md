README
======

Copyright 2012-2013 Daniel Zulla. All rights reserved.
All rights reserved.

This is the preprocessor package of Nexfiltrate.

Requirements
============

Install our super fancy version of PHP.

`
cd ../runtime
sudo ./build.sh
`

Example
=======

Run `php iced.php test.php` to see if it works.

Expected Output
===============

    Hello World!
    SELECT * FROM users WHERE name = 'UNHEX(576f726c64)'
    Taint escalation for an include() statement. Payload: World


