<?php

declare(strict_types=1);

namespace App\Downloader;

class XmlDownloader
{
    public function __construct(protected string $xmlSrc)
    {
    }

    public function download($src)
    {
        $data = fopen($src, 'rb');

        file_put_contents($this->xmlSrc, $data);
    }
}
