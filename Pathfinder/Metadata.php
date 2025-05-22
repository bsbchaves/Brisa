<?php

namespace Brisa\Pathfinder;

trait Metadata
{
    private static null|string $title = null;
    private static null|string $display = null;
    protected static array $metadata = [];

    public const SEP_TITLE = '&#183;';

    public static function title(string $title): void
    {
        self::$title = $title;
        self::$metadata['title'] = $title;

        return;
    }

    public static function display(string $name, bool $change_title = false): void
    {
        $name = parent::createPattern($name);

        self::$display = $name;
        self::$metadata['display'] = $name;

        if ($change_title) {
            self::$metadata['title'] = $name . ' ' . self::SEP_TITLE . ' ' . self::$title;
        } else {
            self::$metadata['title'] = self::$title;
        }

        return;
    }
}
