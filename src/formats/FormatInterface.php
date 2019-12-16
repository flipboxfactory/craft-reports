<?php

namespace flipbox\craft\reports\formats;

use flipbox\craft\reports\sources\SourceInterface;
use Psr\Http\Message\StreamInterface;

interface FormatInterface
{
//    /**
//     * The human-readable name of the pre-configured format
//     *
//     * @return string
//     */
//    public function getName(): string;
//
//    /**
//     * The reference name of the pre-configured format
//     *
//     * @return string
//     */
//    public function getHandle(): string;

    /**
     * @param array $config
     * @return void
     */
    public function configure(array $config);

    /**
     * The type of format.  Ex: JSON or CSV
     * @return string
     */
    public static function type(): string;

    /**
     * The file extension, or null if file downloads are not allowed
     *
     * @return string
     */
    public static function fileExtension(): string;

    /**
     * Formats the data source; ready to be output to screen or download to file
     *
     * @param SourceInterface $source
     * @return StreamInterface
     */
    public function run(SourceInterface $source): StreamInterface;
}
