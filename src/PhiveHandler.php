<?php

declare(strict_types=1);

/**
 * Copyright bitExpert
 *
 * Licenses under the MIT-license. For details see the included file LICENSE.md
 */

namespace bitExpert\ComposerPhiveBridge;


use Composer\IO\IOInterface;
use function chmod;
use function dirname;
use function feof;
use function file_exists;
use function fwrite;
use function is_executable;
use function realpath;
use function sprintf;

class PhiveHandler
{
	private $path;

	private $workingDirectory;

	private $io;

	public function __construct(string $path, IOInterface $io)
	{
		$this->workingDirectory = realpath($path);
		$this->path = $this->workingDirectory . '/tools/phive';
		$this->io = $io;
	}

	public function assertPhiveAvailability(): self
	{
		exec('which phive', $result);
		if (count($result) > 0) {
			$this->path = $result[0];
			$this->io->write(sprintf(
				'Found phive at %s',
				$this->path
			));
			return $this;
		}
		$this->installPhive();
		$this->io->write(sprintf(
			'Found phive at %s',
			$this->path
		));
		return $this;
	}

	public function install(): self
	{
		if (! file_exists($this->workingDirectory . '/phive.xml') &&
			! file_exists($this->workingDirectory . '/.phive/phars.xml')) {
			$this->io->write(sprintf(
				'Could not find a config-file for phive at %s',
				$this->workingDirectory
			));
			return $this;
		}
		$this->io->write(sprintf(
			'Running "%s install"',
			$this->path
		));
		exec($this->path . ' install', $result);
		foreach ($result as $line){
			$this->io->write("\t" . $line);
		}

		return $this;
	}

	private function installPhive(): void
	{
		if (! file_exists(dirname($this->path))) {
			mkdir(dirname($this->path), 0777, true);
		}
		if (file_exists($this->path) && is_executable($this->path)) {
			return;
		}
		$this->io->write('Downloading phive...');

		$fi = fopen('https://phar.io/releases/phive.phar', 'r');
		$fo = fopen($this->path, 'w+');
		while(! feof($fi)) {
			fwrite($fo, fread($fi, 1024));
		}
		fclose($fi);
		fclose($fo);

		chmod($this->path, 0777 & ~umask());
	}

}
