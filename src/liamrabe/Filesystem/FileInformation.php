<?php
namespace liamrabe\Filesystem;

class FileInformation {

	const PROPS = [
		'dirname' => 'dirname',
		'filename' => 'basename',
		'last_modified' => 'filemtime',
		'file_type' => 'filetype',
		'file_size' => 'filesize',
	];

	protected ?string $dirname = null;
	protected ?string $filename = null;
	protected ?int $last_modified = null;
	protected ?string $file_type = null;
	protected ?int $file_size = null;
	protected ?string $full_path = null;

	public function __construct(string $path = null) {
		foreach (self::PROPS as $key => $function) {
			$this->$key = $function($path);
		}

		$this->full_path = $path;
	}

	/**
	 * @return string|null
	 */
	public function getDirname(): ?string {
		return $this->dirname;
	}

	/**
	 * @return string|null
	 */
	public function getFilename(): ?string {
		return $this->filename;
	}

	/**
	 * @return int|null
	 */
	public function getLastModified(): ?int {
		return $this->last_modified;
	}

	/**
	 * @return string|null
	 */
	public function getFileType(): ?string {
		return $this->file_type;
	}

	/**
	 * @return int|null
	 */
	public function getFileSize(): ?int {
		return $this->file_size;
	}

	/**
	 * @return string|null
	 */
	public function getFullPath(): ?string {
		return $this->full_path;
	}

}