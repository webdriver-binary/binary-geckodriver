<?php

declare(strict_types=1);

namespace WebdriverBinary\GeckoDriver\Plugin;

use WebdriverBinary\WebDriverBinaryDownloader\Interfaces\PlatformAnalyserInterface as Platform;

class Config implements \WebdriverBinary\WebDriverBinaryDownloader\Interfaces\ConfigInterface
{
    /**
     * @var \Composer\Package\PackageInterface
     */
    private $configOwner;

    /**
     * @param \Composer\Package\PackageInterface $configOwner
     */
    public function __construct(
        \Composer\Package\PackageInterface $configOwner
    ) {
        $this->configOwner = $configOwner;
    }

    public function getPreferences(): array
    {
        $extra = $this->configOwner->getExtra();

        $defaults = [
            'version' => null,
        ];

        return array_replace(
            $defaults,
            isset($extra['geckodriver']) ? $extra['geckodriver'] : []
        );
    }

    public function getDriverName(): string
    {
        return 'GeckoDriver';
    }
    
    public function getRequestUrlConfig(): array
    {
        $baseUrl = 'https://github.com/mozilla/geckodriver/releases/download';
        
        return [
            self::REQUEST_VERSION => false,
            self::REQUEST_DOWNLOAD => sprintf('%s/v{{version}}/{{file}}', $baseUrl),
        ];
    }
    
    public function getBrowserBinaryPaths(): array
    {
        return [
            Platform::TYPE_LINUX32 => [
                '/usr/bin/firefox',
            ],
            Platform::TYPE_LINUX64 => [
                '/usr/bin/firefox',
            ],
            Platform::TYPE_MAC64 => [
                '/Applications/Firefox.app/Contents/MacOS/firefox',
            ],
            Platform::TYPE_WIN32 => [
                'C:\\\\Program Files (x86)\\\\Mozilla Firefox\\\\firefox.exe',
            ],
            Platform::TYPE_WIN64 => [
                'C:\\\\Program Files (x86)\\\\Mozilla Firefox\\\\firefox.exe',
            ],
        ];
    }
    
    public function getBrowserVersionPollingConfig(): array
    {
        return [
            '%s -v' => ['Mozilla Firefox ([0-9].+)'],
            'wmic datafile where name="%s" get Version /value' => ['Version=([0-9].+)'],
        ];
    }
    
    public function getDriverVersionPollingConfig(): array
    {
        return [
            '%s --version' => ['geckodriver ([0-9].+) \('],
        ];
    }
    
    public function getBrowserDriverVersionMap(): array
    {
        return [
            '66' => '',
            '65' => '0.24.0',
            '57' => '0.23.0',
            '55' => '0.20.1',
            '1' => '0.16.1',
        ];
    }
    
    public function getRemoteFileNames(): array
    {
        return [
            Platform::TYPE_LINUX32 => 'geckodriver-v{{version}}-linux32.tar.gz',
            Platform::TYPE_LINUX64 => 'geckodriver-v{{version}}-linux64.tar.gz',
            Platform::TYPE_MAC64 => 'geckodriver-v{{version}}-macos.tar.gz',
            Platform::TYPE_WIN32 => 'geckodriver-v{{version}}-win32.zip',
            Platform::TYPE_WIN64 => 'geckodriver-v{{version}}-win64.zip',
        ];
    }

    public function getExecutableFileNames(): array
    {
        return [
            Platform::TYPE_LINUX32 => 'geckodriver',
            Platform::TYPE_LINUX64 => 'geckodriver',
            Platform::TYPE_MAC64 => 'geckodriver',
            Platform::TYPE_WIN32 => 'geckodriver.exe',
            Platform::TYPE_WIN64 => 'geckodriver.exe',
        ];
    }

    public function getDriverVersionHashMap(): array
    {
        return [];
    }

    public function getExecutableFileRenames(): array
    {
        return [];
    }
}
