<?php

use Exception\FilesystemException;

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
		return $this->content ?? null;
	}

	/**
	 * @return bool
	 */
	public function exists(): bool {
		return file_exists($this->path);
	}

	/**
	 * @param string $mode
	 * @return Filesystem
	 * @throws FilesystemException
	 */
	public function setMode(string $mode): Filesystem {
		if (!in_array($mode, self::MODES)) {
			throw new FilesystemException("You can't save file in read mode");
		}

		return $this;
	}

	/* STATIC METHODS */

	/**
	 * Generate filesystem class on formatted filepath
	 *
	 * @param $_
	 * @return Filesystem
	 */
	public static function path($_): Filesystem {
		$args = func_get_args();
		$format = array_shift($args);

		$path = str_replace('\\', '/', vsprintf($format, $args));
		return new self($path);
	}

}