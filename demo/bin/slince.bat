@echo.
@echo off

SET app=%0
SET lib=%~dp0

php "%lib%slince.php" %*

echo.

exit /B %ERRORLEVEL%
