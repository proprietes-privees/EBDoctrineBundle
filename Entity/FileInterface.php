<?php

namespace EB\DoctrineBundle\Entity;

/**
 * Class FileInterface
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
interface FileInterface
{
    /**
     * Get ID
     *
     * @return int
     */
    public function getId();

    /**
     * Get file
     *
     * @return null|\SplFileInfo
     */
    public function getFile();

    /**
     * Set file
     *
     * @param null|\SplFileInfo $file
     *
     * @return FileInterface
     */
    public function setFile(\SplFileInfo $file = null);

    /**
     * Get path
     *
     * @return string
     */
    public function getPath();

    /**
     * Set path
     *
     * @param string $path
     *
     * @return FileInterface
     */
    public function setPath($path);

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename();

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return FileInterface
     */
    public function setFilename($filename);

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension();

    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return FileInterface
     */
    public function setExtension($extension);

    /**
     * Get size
     *
     * @return int
     */
    public function getSize();

    /**
     * Set size
     *
     * @param int $size
     *
     * @return FileInterface
     */
    public function setSize($size);

    /**
     * Get mime
     *
     * @return string
     */
    public function getMime();

    /**
     * Set mime
     *
     * @param string $mime
     *
     * @return FileInterface
     */
    public function setMime($mime);

    /**
     * Get key used to store files in different
     * cache directories
     *
     * @return null|string
     */
    public function getCacheKey();
}
