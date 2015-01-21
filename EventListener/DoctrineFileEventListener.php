<?php

namespace EB\DoctrineBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use EB\DoctrineBundle\Entity\FileInterface;
use EB\DoctrineBundle\Entity\FileReadableInterface;
use EB\DoctrineBundle\Entity\FileVersionableInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class DoctrineFileEventListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class DoctrineFileEventListener
{
    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * @var string
     */
    private $kernelRootDir;

    /**
     * @var string
     */
    private $webPath;

    /**
     * @var string
     */
    private $securedPath;

    /**
     * @var bool
     */
    private $useEnvDiscriminator;

    /**
     * @var bool
     */
    private $useClassDiscriminator;

    /**
     * @var int
     */
    private $depth;

    /**
     * @var string
     */
    private $env;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Filesystem      $fs                    Filesystem
     * @param string          $kernelRootDir         Kernel root directory
     * @param string          $webPath               Web path
     * @param string          $securedPath           Secured path
     * @param bool            $useEnvDiscriminator   Use env in paths
     * @param bool            $useClassDiscriminator Use class in paths
     * @param int             $depth                 Tree directory depth
     * @param string          $env                   Environment
     * @param LoggerInterface $logger                Logger
     */
    public function __construct(Filesystem $fs, $kernelRootDir, $webPath, $securedPath, $useEnvDiscriminator, $useClassDiscriminator, $depth, $env, LoggerInterface $logger)
    {
        $this->fs = $fs;
        $this->kernelRootDir = $kernelRootDir;
        $this->webPath = '/' . trim($webPath, '/');
        $this->securedPath = rtrim($securedPath, '/');
        $this->useEnvDiscriminator = (bool)$useEnvDiscriminator;
        $this->useClassDiscriminator = (bool)$useClassDiscriminator;
        $this->depth = $depth;
        $this->env = $env;
        $this->logger = $logger;
    }

    /**
     * Before creation and update, we must
     * complete the entity details
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof FileInterface) {
            if (null !== $file = $entity->getFile()) {
                if (null === $entity->getPath() || $entity->getPath() !== $file->getRealPath()) {
                    $entity
                        ->setFilename($file->getFilename())
                        ->setSize($file->getSize())
                        ->setExtension($file->getExtension());

                    // MD5
                    if (file_exists($file->getRealPath()) && is_readable($file->getRealPath())) {
                        if (false !== $md5 = md5_file($file->getRealPath())) {
                            $entity->setMd5($md5);
                        }
                    }

                    // Improve data with uploaded file details
                    if ($file instanceof UploadedFile) {
                        $entity
                            ->setFilename($file->getClientOriginalName())
                            ->setExtension($file->guessExtension())
                            ->setMime($file->getClientMimeType());
                    } elseif (function_exists('finfo_open')) {
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $entity->setMime(finfo_file($finfo, $file->getRealPath()));
                    }
                }
            }
        }
    }

    /**
     * Before creation and update, we must
     * complete the entity details
     *
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof FileInterface) {
            if (null !== $file = $entity->getFile()) {
                if (null === $entity->getPath() || $entity->getPath() !== $file->getRealPath()) {
                    $entity
                        ->setFilename($file->getFilename())
                        ->setSize($file->getSize())
                        ->setExtension($file->getExtension());

                    // MD5
                    if (file_exists($file->getRealPath()) && is_readable($file->getRealPath())) {
                        if (false !== $md5 = md5_file($file->getRealPath())) {
                            $entity->setMd5($md5);
                        }
                    }

                    // Improve data with uploaded file details
                    if ($file instanceof UploadedFile) {
                        $entity
                            ->setFilename($file->getClientOriginalName())
                            ->setExtension($file->guessExtension())
                            ->setMime($file->getClientMimeType());
                    }

                    // Improve data with finfo
                    if (null === $entity->getMime() && function_exists('finfo_open')) {
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $entity->setMime(finfo_file($finfo, $file->getRealPath()));
                    }
                }
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->postLoadFile($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->postLoadFile($args);
    }

    /**
     * After creation and update, we have to
     * save the file in the filesystem
     *
     * @param LifecycleEventArgs $args Doctrine arguments
     */
    private function postLoadFile(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof FileInterface) {
            if (null !== $file = $entity->getFile()) {
                if ($file->getRealPath() !== $entity->getPath()) {
                    // Choose this filesystem directory separator
                    $s = DIRECTORY_SEPARATOR;

                    // Save file in different folders according to there class
                    // eg: /var/www/data/user for User entity
                    $class = null;
                    if ($this->useClassDiscriminator) {
                        if (null === $class = $entity->getCacheKey()) {
                            $class = '';
                        }
                    }

                    // Create a tree using this current file ID
                    // eg: ID 1259 will create a tree like /0/0/0/1/2/5/9
                    // The actual depth of the tree is a configuration
                    $tree = $entity->getId();
                    if (0 < $this->depth) {
                        $tree = implode($s, str_split(str_pad($tree, $this->depth, '0', STR_PAD_LEFT), 1));
                    }

                    // Increase version if this is a versionable resource
                    if ($entity instanceof FileVersionableInterface) {
                        $entity->setVersion(1 + $entity->getVersion());
                    }

                    // Create a path
                    // - Choose the path (wether it's readable or not)
                    // - Use environment folder or not
                    // - Use class folder or not
                    // - Use tree
                    // - Use version or not
                    $path = sprintf(
                        '%s%s%s%s%s%s.%s',
                        $entity instanceof FileReadableInterface ? ($this->kernelRootDir . $s . '..' . $s . 'web' . $this->webPath) : $this->securedPath,
                        $s,
                        true === $this->useEnvDiscriminator ? $this->env . $s : '',
                        null === $class ? '' : $class . $s,
                        $tree,
                        $entity instanceof FileVersionableInterface ? sprintf('-%u', $entity->getVersion()) : '',
                        $entity->getExtension()
                    );
                    $this->logger->debug(__METHOD__ . ' : path = ' . $path);

                    // Save this file
                    try {
                        // Create the holding folder
                        $this->fs->mkdir(pathinfo($path, PATHINFO_DIRNAME));

                        // Move the downloaded file to its new location
                        $this->fs->rename($file->getRealPath(), $path, true);

                        // Remove file object from the entity and fix the real path
                        $entity
                            ->setFile(null)
                            ->setPath(realpath($path));

                        // If this file is readable, save its URI too
                        if ($entity instanceof FileReadableInterface) {
                            $uri = sprintf(
                                '%s/%s%s%s%s.%s',
                                $this->webPath,
                                $this->useEnvDiscriminator ? $this->env . '/' : '',
                                null === $class ? '' : $class . $s,
                                $tree,
                                $entity instanceof FileVersionableInterface ? '-' . $entity->getVersion() : '',
                                $entity->getExtension()
                            );
                            $this->logger->debug(__METHOD__ . ' : uri = ' . $uri);
                            $entity->setUri($uri);
                        }

                        // Save
                        $this->logger->debug(__METHOD__ . ' : saving in database');
                        $args->getEntityManager()->flush();
                        $this->logger->debug(__METHOD__ . ' : saved');
                    } catch (IOException $e) {
                        $this->logger->error(__METHOD__ . ' filesystem error : ' . $e->getMessage());

                        throw $e;
                    } catch (\Exception $e) {
                        $this->logger->error(__METHOD__ . ' : ' . $e->getMessage());

                        throw $e;
                    }
                }
            }
        }
    }

    /**
     * Remove file from filesystem
     * when entity is deleted
     *
     * @param LifecycleEventArgs $args Doctrine arguments
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof FileInterface) {
            if (null !== $path = $entity->getPath()) {
                if (is_file($path)) {
                    $this->fs->remove($path);
                }
            }
        }
    }
}
