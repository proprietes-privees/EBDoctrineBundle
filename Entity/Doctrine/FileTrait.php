<?php

namespace EB\DoctrineBundle\Entity\Doctrine;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait FileTrait
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
trait FileTrait
{
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $filename;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $extension;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $mime;

    /**
     * @var null|string
     * @ORM\Column(type="string", nullable=true)
     */
    private $path;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @var null|\SplFileInfo
     * @Assert\File()
     */
    private $file;

    /**
     * Set Filename
     *
     * @param string $filename
     *
     * @return FileTrait
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get Filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set Extension
     *
     * @param string $extension
     *
     * @return FileTrait
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get Extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set Mime
     *
     * @param string $mime
     *
     * @return FileTrait
     */
    public function setMime($mime)
    {
        $this->mime = $mime;

        return $this;
    }

    /**
     * Get Mime
     *
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Set Path
     *
     * @param null|string $path
     *
     * @return FileTrait
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get Path
     *
     * @return null|string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set Size
     *
     * @param int $size
     *
     * @return FileTrait
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get Size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set File
     *
     * @param null|\SplFileInfo $file
     *
     * @return FileTrait
     */
    public function setFile(\SplFileInfo $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get File
     *
     * @return null|\SplFileInfo
     */
    public function getFile()
    {
        return $this->file;
    }
}
