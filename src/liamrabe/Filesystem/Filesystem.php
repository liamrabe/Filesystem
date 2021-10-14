<?php
namespace liamrabe\Filesystem;

use liamrabe\Filesystem\Exception\InvalidFileModeException;
use liamrabe\Filesystem\Exception\FileNotFoundException;
use Exception;

use SimpleXMLElement;
use stdClass;

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

	protected string $mode;

	public function __construct(string $path, int $mode = self::MODE_READ) {
		$this->setMode($mode);
		$this->path = $path;
	}

	/**
	 * @param string $content
	 * @return Filesystem
	 */
	public function setContent(string $content): Filesystem {
		$this->content = $content;
		$this->setMode(self::MODE_WRITE);

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

	/**
	 * Parses content to JSON
	 *
	 * @return stdClass|bool
	 * @throws Exception
	 */
	public function getContentAsJSON(): stdClass|bool {
		$json = json_decode($this->getContent());

		if (!$json) {
			throw new Exception('Failed parsing JSON');
		}

		return $json;
	}

	/**
	 * Parses content to XML
	 *
	 * @return SimpleXMLElement
	 * @throws Exception
	 */
	public function getContentAsXML(): SimpleXMLElement {
		return new SimpleXMLElement($this->getContent());
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
		return new FileInformation($this->path, $this->getContent());
	}

	/**
	 * Save the current file
	 *
	 * @param string $flags
	 * @return bool
	 */
	public function save(string $flags): bool {
		$file = fopen($this->path, $flags);
		fwrite($file, $this->content);

		return fclose($file);
	}

	/**
	 * Moves file from one folder to another
	 *
	 * @param string $new_file_path
	 * @return Filesystem|bool
	 */
	public function move(string $new_file_path): Filesystem|bool {
		$content = $this->getContent();
		$copy_res = $this->save($new_file_path, $content, 'x+');

		if ($copy_res) {
			$this->delete($this->path);

			return new self($new_file_path);
		}

		return false;
	}

	/**
	 * Deletes a file from the filesystem.
	 *
	 * @param string $path
	 * @return bool
	 */
	public function delete(string $path = ''): bool {
		if ($path === '') {
			$path = $this->path;
		}

		return unlink($path);
	}

	/**
	 * @param int $mode
	 * @return Filesystem
	 * @throws InvalidFileModeException
	 */
	protected function setMode(int $mode): Filesystem {
		if (!in_array($mode, self::MODES)) {
			throw new InvalidFileModeException("Invalid filesystem mode, '%s' is not supported", $mode);
		}

		$this->mode = self::MODES[$mode];
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

	/**
	 * Generate filesystem class on formatted filepath
	 *
	 * @param $_
	 * @return Filesystem
	 * @throws FileNotFoundException
	 */
	public static function create($_): Filesystem {
		$args = func_get_args();
		$format = array_shift($args);

		$path = str_replace('\\', '/', vsprintf($format, $args));

		if (file_exists($path)) {
			return self::path($path);
		}

		return new self($path, self::MODE_WRITE);
	}

}