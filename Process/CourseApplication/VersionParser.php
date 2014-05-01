<?php
namespace Ice\FormBundle\Process\CourseApplication;

class VersionParser
{
    public function getMajorVersion($versionString)
    {
        return explode('.', $versionString)[0];
    }
}
