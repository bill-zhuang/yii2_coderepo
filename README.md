# yii2_coderepo
yii2 coderepo

# Installing
1. user composer to install Yii 2 & corresponding libs, command: composer install
2. config file `yii` at root directory, set php path (#!/usr/bin/env php) if needed, ie. #!D:/WAMP/php-5.5.6-Win32-VC11-x64/php.exe
3. run yii on console, command: yii migrate
4. config `RbacController.php` at directory `\console\controllers\`, set admin name
5. run yii on console, command: yii rbac/init, if success, db backend_user create user admin admin.
6. Done.

TODO.
1. backend acl feature not available yet.
2. backend log feature not trigger yet.