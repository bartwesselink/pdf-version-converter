<?php

/*
 * This file is part of the PDF Version Converter.
 *
 * (c) Thiago Rodrigues <xthiago@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Xthiago\PDFVersionConverter\Converter;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Converter that uses ghostscript to change PDF version.
 *
 * @author Thiago Rodrigues <xthiago@gmail.com>
 */
class GhostscriptConverter implements ConverterInterface
{
    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var GhostscriptConverterCommand
     */
    protected $command;

    /**
     * Directory where temporary files are stored.
     * @var string
     */
    protected $tmp;

    /**
     * @param GhostscriptConverterCommand $command
     * @param Filesystem $fs
     * @param null|string $tmp
     */
    public function __construct(GhostscriptConverterCommand $command, Filesystem $fs, $tmp = null)
    {
        $this->command = $command;
        $this->fs = $fs;
        $this->tmp = $tmp ? : sys_get_temp_dir();
    }

    /**
     * Generates a unique absolute path for tmp file.
     * @return string absolute path
     */
    protected function generateAbsolutePathOfTmpFile()
    {
        return $this->tmp .'/'. uniqid('pdf_version_changer_') . '.pdf';
    }

    /**
     * {@inheritdoc }
     */
    public function convert($gsExecutable, $files, $newVersion)
    {
        $tmpFile = $this->generateAbsolutePathOfTmpFile();

        if (!is_array($files)) {
            $files = [$files];
        }

        $this->command->run($gsExecutable, $files, $tmpFile, $newVersion);

        if (!$this->fs->exists($tmpFile))
            throw new \RuntimeException("The generated file '{$tmpFile}' was not found.");

        return $tmpFile;
    }
}
