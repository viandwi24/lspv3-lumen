<?php

namespace App\Helpers;

class Version
{
    protected $config = [];

    function __construct()
    {
        $this->config = [
            'major' => env('VERSION_MAJOR', 0),
            'minor' => env('VERSION_MINOR', 0),
            'patch' => env('VERSION_PATCH', 0),
            'prerelease' => env('VERSION_PRE_RELEASE', 0),
            'format' => env('VERSION_FORMAT', 0),
            'buildmetadata' => env('VERSION_BUILD_METADATA', 0),
            'label' => env('VERSION_LABEL', 0),
        ];
    }

    public function format($pattern = null)
    {
        $format = ($pattern == null ? $this->config['format'] : $pattern);
        return str_replace(
            [
                ':major',
                ':minor',
                ':patch',
                ':prerelease',
                ':format',
                ':buildmetadata',
                ':label',
            ],
            [
                $this->config['major'],
                $this->config['minor'],
                $this->config['patch'],
                $this->config['prerelease'],
                $this->config['format'],
                $this->config['buildmetadata'],
                $this->config['label'],
            ],
            $format
        );
    }

    public function getCommit()
    {
        $commitHash = trim(exec('git log --pretty="%h" -n1 HEAD'));
        $commitDate = new \DateTime(trim(exec('git log -n1 --pretty=%ci HEAD')));
        $commitDate->setTimezone(new \DateTimeZone('UTC'));
        return [
            'hash' => $commitHash,
            'date' => $commitDate->format('Y-m-d H:i:s'),
        ];
    }
}