<?php

namespace Bref\LaravelBridge;

use RuntimeException;

class StorageDirectories
{
    /**
     * The storage path for the execution environment.
     *
     * @var string
     */
    public const Path = '/tmp/storage';

    /**
     * Ensure the necessary storage directories exist.
     *
     * @return void
     */
    public static function create()
    {
        $directories = [
            // self::Path . '/app',
            // self::Path . '/logs',
            self::Path . '/bootstrap/cache',
            self::Path . '/framework/cache',
            self::Path . '/framework/views',
            self::Path . '/psysh',
        ];

        $directories = array_filter($directories, static fn ($directory) => ! is_dir($directory));

        if (count($directories) && defined('STDERR')) {
            fwrite(STDERR, 'Creating storage directories: ' . implode(', ', $directories) . PHP_EOL);
        }

        $oldumask = umask(0);
        foreach ($directories as $directory) {
            if (! mkdir($directory, 0777, true) && ! is_dir($directory)) {
                umask($oldumask);
                throw new RuntimeException("Directory {$directory} could not be created");
            }
        }
        umask($oldumask);
    }
}
