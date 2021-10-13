<?php
namespace liamrabe\Filesystem;

use Exception\FileNotFoundException;
use Exception\InvalidFileModeException;

class Filesystem {

	/* Filesystem types */
	const TYPES = [
		0 => 'json',
		1 => 'xml',
	];

	const TYPE_JSON = 0;
	const TYPE_XML = 1;

	/* Filesystem modes */
	const MODES = [
		0 => 'read',
		1 => 'write',
	];

	const MODE_READ = 0;
	const MODE_WRITE = 1;

	protected string $path;
	protected string $content;

	protected string $mode = 'read';

	public function __construct(string $path) {
		$this->path = $path;
	}

	/**
	 * @param string $content
	 * @return Filesystem
	 */
	public function setContent(string $content): Filesystem {
		$this->content = $content;
		$this->setMode('write');

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getContent(): ?string {
		if (empty($this->content)) {
			$this->content = $this->readFile();
		}

		return $this->content;
	}

	protected function readFile(): string {
		return file_get_contents($this->path);
	}

	/**
	 * @return bool
	 */
	public function exists(): bool {
		return file_exists($this->path);
	}

	public function getMetadata(): FileInformation {
		if (empty($this->content)) {
			$this->getContent();
		}

		return new FileInformation($this->path, $this->content);
	}

	/**
	 * @param string $mode
	 * @return Filesystem
	 * @throws InvalidFileModeException
	 */
	public function setMode(string $mode): Filesystem {
		if (!in_array($mode, self::MODES)) {
			throw new InvalidFileModeException("You can't save file in read mode");
		}

		return $this;
	}

	/* STATIC METHODS */

	/**
	 * Generate filesystem class on formatted filepath
	 *
	 * @param $_
	 * @return Filesystem
	 * @throws FileNotFoundException
	 */
	public static function path($_): Filesystem {
		$args = func_get_args();
		$format = array_shift($args);

		$path = str_replace('\\', '/', vsprintf($format, $args));

		if (!file_exists($path)) {
			throw new FileNotFoundException(sprintf(
				"Couldn't find file %s",
				basename($path)
			));
		}

		return new self($path);
	}

}