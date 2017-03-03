@echo off

set PTOOLSPATH=%~dp0\..\src\app\vendor\phalcon\devtools\

cd %~dp0\..\src\

php %PTOOLSPATH%phalcon.php %*