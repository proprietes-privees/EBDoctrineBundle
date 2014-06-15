<?php

namespace EB\DoctrineBundle\Entity\Doctrine;

use Doctrine\ORM\Mapping as ORM;
use EB\DoctrineBundle\Entity\FileInterface;
use EB\DoctrineBundle\Entity\FileReadableInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait FileTrait
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
trait FileTrait
{
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $filename;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $extension;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $mime;

    /**
     * @var null|string
     * @ORM\Column(type="string", nullable=true)
     */
    private $path;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $size;

    /**
     * This uniqid is used to trigger doctrine
     * events each time setFile is called (because
     * the file field is not handled by doctrine)
     *
     * @var null|string
     * @ORM\Column(type="string", length=13, nullable=true)
     * @Assert\Length(max=13)
     */
    private $uniqid;

    /**
     * @var null|\SplFileInfo
     * @Assert\File()
     */
    private $file;

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
     * Get Extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
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
     * Get Mime
     *
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
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
     * Get Size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
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
     * Get File
     *
     * @return null|\SplFileInfo
     */
    public function getFile()
    {
        return $this->file;
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

        $this->setUniqid(uniqid());

        return $this;
    }

    /**
     * Get Uniqid
     *
     * @return null|string
     */
    public function getUniqid()
    {
        return $this->uniqid;
    }

    /**
     * Set Uniqid
     *
     * @param null|string $uniqid Uniqid
     *
     * @return FileTrait
     */
    public function setUniqid($uniqid)
    {
        $this->uniqid = $uniqid;

        return $this;
    }

    /**
     * Remove all file trace
     *
     * @return FileTrait
     */
    public function removeFile()
    {
        if ($this instanceof FileInterface) {
            $this->setComputedPath(null);
        }
        if ($this instanceof FileReadableInterface) {
            $this->setComputedUri(null);
        }

        $this
            ->setExtension(null)
            ->setFilename(null)
            ->setMime(null)
            ->setSize(null);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getComputedPath()
    {
        return $this->getPath();
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
     * {@inheritdoc}
     */
    public function setComputedPath($path)
    {
        return $this->setPath($path);
    }
}
