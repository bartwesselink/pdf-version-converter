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

use Symfony\Component\Process\Process;

/**
 * Encapsulates the knowledge about gs command.
 *
 * @author Thiago Rodrigues <xthiago@gmail.com>
 */
class GhostscriptConverterCommand
{
    /**
     * @var string
     */
    protected $baseCommand = ' -sDEVICE=pdfwrite -dCompatibilityLevel=%s -dPDFSETTINGS=/screen -dNOPAUSE -dQUIET -dBATCH -sOutputFile=%s %s';

    public function __construct()
    {
    }

    public function run($executable, $originalFiles, $newFile, $newVersion)
    {
        $escapedFiles = [];
        foreach ($originalFiles as $file) {
            $escapedFiles[] = escapeshellarg($file);
        }

        $command = $executable.sprintf($this->baseCommand, $newVersion, $newFile, implode(' ', $escapedFiles));

        exec($command);

        if (!file_exists($newFile)) {
            throw new \RuntimeException();
        }
    }
}
