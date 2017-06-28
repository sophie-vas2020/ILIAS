<?php

namespace ILIAS\Filesystem\Provider\FlySystem;;

use ILIAS\Filesystem\DTO\Metadata;
use ILIAS\Filesystem\Exception\DirectoryNotFoundException;
use ILIAS\Filesystem\Exception\IOException;
use ILIAS\Filesystem\Provider\DirectoryAccess;
use ILIAS\Filesystem\Visibility;
use League\Flysystem\FilesystemInterface;

/**
 * Class FlySystemDirectoryAccess
 *
 * @author  Nicolas Schäfli <ns@studer-raimann.ch>
 * @since 5.3
 * @version 1.0.0
 */
class FlySystemDirectoryAccess implements DirectoryAccess {

	/**
	 * @var FilesystemInterface $flySystemFS
	 */
	private $flySystemFS;


	/**
	 * FlySystemDirectoryAccess constructor.
	 *
	 * @param FilesystemInterface $flySystemFS   A configured fly system filesystem instance.
	 */
	public function __construct(FilesystemInterface $flySystemFS) { $this->flySystemFS = $flySystemFS; }


	/**
	 * Checks whether the directory exists or not.
	 *
	 * @param string $path The path which should be checked.
	 *
	 * @return bool True if the directory exists otherwise false.
	 *
	 * @since   5.3
	 * @version 1.0
	 */
	public function hasDir($path) {
		// TODO: Implement hasDir() method.
	}


	/**
	 * Lists the content of a directory.
	 *
	 * @param string $path      The directory which should listed. Defaults to the adapter root directory.
	 * @param bool   $recursive Set to true if the child directories also should be listed. Defaults to false.
	 *
	 * @return Metadata[]           An array of metadata about all known files, in the given directory.
	 *
	 * @throws DirectoryNotFoundException If the directory is not found or inaccessible.
	 *
	 * @since   5.3
	 * @version 1.0
	 */
	public function listContents($path = '', $recursive = false) {
		// TODO: Implement listContents() method.
	}


	/**
	 * Create a new directory.
	 *
	 * Please note that the Visibility interface defines two constants PUBLIC_ACCESS and PRIVATE_ACCESS
	 * to ease the development process.
	 *
	 * @param string $path       The directory path which should be created.
	 * @param string $visibility The visibility of the directory. Defaults to visibility public.
	 *
	 * @return void
	 *
	 * @throws IOException                  If the directory could not be created.
	 * @throws \InvalidArgumentException     If the visibility is not 'public' or 'private'.
	 *
	 * @since   5.3
	 * @version 1.0
	 */
	public function createDir($path, $visibility = Visibility::PUBLIC_ACCESS) {
		// TODO: Implement createDir() method.
	}


	/**
	 * Copy all childes of the source recursive to the destination.
	 * The file access rights will be copied as well.
	 *
	 * The operation will fail fast if the destination directory is not empty.
	 * All destination folders will be created if needed.
	 *
	 * @param string $source      The source which should be scanned and copied.
	 * @param string $destination The destination of the recursive copy.
	 *
	 * @throws IOException                  Thrown if the directory could not be copied.
	 * @throws DirectoryNotFoundException   Thrown if the source directory could not be found.
	 *
	 * @return void
	 *
	 * @since   5.3
	 * @version 1.0
	 */
	public function copyDir($source, $destination) {
		// TODO: Implement copyDir() method.
	}


	/**
	 * Deletes a directory recursive.
	 *
	 * @param string $path The path which should be deleted.
	 *
	 * @return void
	 *
	 * @throws IOException If the path could not be deleted.
	 *
	 * @since   5.3
	 * @version 1.0
	 */
	public function deleteDir($path) {
		// TODO: Implement deleteDir() method.
	}
}