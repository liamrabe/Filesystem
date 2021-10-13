# Filesystem
An easy-to-use filesystem-class for PHP 8.0 and above.

# Installation

Run `composer require liamrabe/filesystem` to add it to your project

# Requirements

* PHP >=8.0
* Composer 2

# Usage

`liamrabe/Filesystem` uses function-chaining so you just need to initialize it with the
`path()`-function, like this:

```php
$file = Filesystem::path('/path/to/file');
```

## Functions

### getContent() (string)
Returns the file's content.

```php
$file->getContent(); // Returns: Lorem ipsum dolor sit amet.
```

### setContent() (Filesystem)
Sets the file's content.

```php
$file->setContent('Hello, world'); // Returns: itself.
```

### exists() (bool)
Returns `true` or `false` depending on if the file exists or not.

```php
$file->exists(); // Returns: true or false
```

### setMode(int $mode) [protected]
Changes the filesystem "mode" to either `read` or `write`.

```php
// This sets the filesystem mode to write
$this->setMode(Filesystem::MODE_WRITE);
```

### getMetadata() (FileInformation)
Initializes `FileInformation`-class with metadata about the file.

#### getDirname() (string)
Returns current file's directory path.

#### getFileName() (string)
Returns the filename of the current file.

#### getLastModified() (int)
Returns the unix timestamp when the file was last modified.

#### getFileType() (string)
Returns the filetype.

#### getFullPath() (string)
Returns the full path to the file.

## Function chaining
You can use function-chaining to get parts of a file or metadata.

Here's an example using function-chaining and string format.

```php
use liamrabe\Filesystem;

require "./vendor/autoload.php";

$dirname = '/path/to/file';
$filename = 'file.txt';

echo Filesystem::path('/%s/%s', $dirname, $filename)
        ->getMetadata()
            ->getDirname();

// Returns: '/path/to/file'
```