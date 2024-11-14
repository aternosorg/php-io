# aternos/io

PHP library for IO operations.

The main goal of this library is to abstract IO operations to make different kind
of IO elements, e.g. files and streams, interchangeable and allow writing code that
doesn't depend on the filesystem, but feature interfaces that can be implemented for 
any kind of IO backend, e.g. network based storages.

## Installation

```bash
composer require aternos/io
```

## Basic Usage

```php
use Aternos\IO\System\File\File;
use Aternos\IO\System\Directory\Directory;

$file = new File("path/to/file.txt");
$file->create();
$file->write("Hello World!");
$file->setPosition(0);
echo $file->read($file->getSize());
$file->delete();

$directory = new Directory("path/to/directory");
foreach ($directory->getChildrenRecursive() as $child) {
    echo $child->getPath() . PHP_EOL;
}
$directory->delete();
```

## IO Elements

| Name                   | Class                                                                                                 | Description                                                           |
|------------------------|-------------------------------------------------------------------------------------------------------|-----------------------------------------------------------------------|
| **File**               | [`Aternos\IO\System\File\File`](src/System/File/File.php)                                             | Regular filesystem file                                               |
| **TempDiskFile**       | [`Aternos\IO\System\File\TempDiskFile`](src/System/File/TempDiskFile.php)                             | Temporary disk file, created and deleted automatically                |
| **TempMemoryFile**     | [`Aternos\IO\System\File\TempMemoryFile`](src/System/File/TempMemoryFile.php)                         | Temporary file in memory                                              |
| **TempMemoryDiskFile** | [`Aternos\IO\System\File\TempMemoryDiskFile`](src/System/File/TempMemoryDiskFile.php)                 | Temporary file in memory, moved to disk when size exceeds a threshold |
| **Directory**          | [`Aternos\IO\System\Directory\Directory`](src/System/Directory/Directory.php)                         | Regular filesystem directory                                          |
| **TempDirectory**      | [`Aternos\IO\System\Directory\TempDirectory`](src/System/Directory/TempDirectory.php)                 | Temporary directory, created and deleted automatically                |
| **FilteredDirectory**  | [`Aternos\IO\System\Directory\FilteredDirectory`](src/System/Directory/FilteredDirectory.php)         | Directory that filters its children                                   |
| **Link**               | [`Aternos\IO\System\Link\Link`](src/System/Link/Link.php)                                             | Generic filesystem link                                               |
| **FileLink**           | [`Aternos\IO\System\Link\FileLink`](src/System/Link/FileLink.php)                                     | Filesystem link to a file, can be used like a file                    |
| **DirectoryLink**      | [`Aternos\IO\System\Link\DirectoryLink`](src/System/Link/DirectoryLink.php)                           | Filesystem link to a directory, can be used like a directory          |
| **SocketStream**       | [`Aternos\IO\System\Socket\Stream\SocketStream`](src/System/Socket/Stream/SocketStream.php)           | Stream for reading from and writing to sockets                        |
| **SocketReadStream**   | [`Aternos\IO\System\Socket\Stream\SocketReadStream`](src/System/Socket/Stream/SocketReadStream.php)   | Stream for read only sockets                                          |
| **SocketWriteStream**  | [`Aternos\IO\System\Socket\Stream\SocketWriteStream`](src/System/Socket/Stream/SocketWriteStream.php) | Stream for write only sockets                                         |

You can get the correct IO element from a path using `FilesystemElement::getIOElementFromPath()`.

```php
use Aternos\IO\System\FilesystemElement;

$element = FilesystemElement::getIOElementFromPath("path/to/element");
```

## Interfaces
You can and should use the provided interfaces when writing your code to make it more flexible and interchangeable.

The basic interface for all IO elements is [`Aternos\IO\Interfaces\IOElementInterface`](src/Interfaces/IOElementInterface.php).

### Feature Interfaces
The feature interfaces define specific features that an IO element can have, e.g. reading or writing. You should limit the required type in your code to the specific features that you need.
All feature interfaces are listed here: [`src/Interfaces/Features`](src/Interfaces/Features).

#### Example
```php
use Aternos\IO\Interfaces\Features\ReadInterface;
use Aternos\IO\Interfaces\Features\GetSizeInterface;

function readEntireFile(ReadInterface&GetSizeInterface $file): string {
    return $file->read($file->getSize());
}
```

### Type Interfaces
For convenience, this library also provides type interfaces that combine multiple feature interfaces for common
IO elements. All type interfaces are listed here: [`src/Interfaces/Types`](src/Interfaces/Types).