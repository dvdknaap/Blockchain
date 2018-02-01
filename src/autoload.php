<?php
/**
 * Simple autoloader, so we don't need Composer just for this.
 */
class autoLoader
{
    public static function register(): void
    {
        spl_autoload_register(function ($class) {
            $class = str_replace('blockChain', 'src', $class);

            $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . 'Class.php';

            if (file_exists($file)) {
                require $file;
                return true;
            }

            return false;
        });
    }
}
