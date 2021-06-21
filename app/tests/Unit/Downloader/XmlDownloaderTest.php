<?php declare(strict_types=1);

namespace App\Tests\Unit\Downloader;

use App\Downloader\XmlDownloader;
use PHPUnit\Framework\TestCase;

final class XmlDownloaderTest extends TestCase
{
    public function testDownload(): void
    {
        $src = __DIR__.'/google.png';
        $downloader = new XmlDownloader($src);
        $downloader->download('https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png');

        $this->assertFileExists($src);

        unlink($src);
    }

}
