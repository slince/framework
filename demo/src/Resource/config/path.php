<?php
define('BASE_PATH', dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR);
define('APP_PATH', BASE_PATH  . 'src' .  DIRECTORY_SEPARATOR);
define('WEB_PATH', BASE_PATH  . 'web' .  DIRECTORY_SEPARATOR);
define('BUNDLE_PATH', BASE_PATH  . 'bundle' .  DIRECTORY_SEPARATOR);

define('RESOURCE_PATH', APP_PATH  . 'Resource' .  DIRECTORY_SEPARATOR);
define('TMP_PATH', RESOURCE_PATH  . 'tmp' .  DIRECTORY_SEPARATOR);
define('CONFIG_PATH', RESOURCE_PATH . 'config' .  DIRECTORY_SEPARATOR);
define('THEME_PATH', RESOURCE_PATH . 'themes' .  DIRECTORY_SEPARATOR);
define('VIEW_PATH', RESOURCE_PATH . 'view' .  DIRECTORY_SEPARATOR);
define('ASSET_PATH', RESOURCE_PATH . 'assets' .  DIRECTORY_SEPARATOR);