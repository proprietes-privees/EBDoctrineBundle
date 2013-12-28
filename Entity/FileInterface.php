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
     * @return int
     */
    public function getId();

    /**
     * @return null|\SplFileInfo
     */
    public function getFile();

    /**
     * @param null|\SplFileInfo $file
     *
     * @return FileInterface
     */
    public function setFile(\SplFileInfo $file = null);

    /**
     * @return string
     */
    public function getComputedPath();

    /**
     * @param string $path
     *
     * @return FileInterface
     */
    public function setComputedPath($path);

    /**
     * @param string $filename
     *
     * @return FileInterface
     */
    public function setFilename($filename);

    /**
     * @return string
     */
    public function getExtension();

    /**
     * @param string $extension
     *
     * @return FileInterface
     */
    public function setExtension($extension);

    /**
     * @param int $size
     *
     * @return FileInterface
     */
    public function setSize($size);

    /**
     * @param string $mime
     *
     * @return FileInterface
     */
    public function setMime($mime);
}
