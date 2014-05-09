<?php

namespace EB\DoctrineBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use EB\DoctrineBundle\Entity\FileInterface;
use EB\DoctrineBundle\Entity\FileReadableInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class DoctrineFileListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class DoctrineFileListener
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
                $this->load($entity, $file);
            }
        }
    }

    /**
     * Load file
     *
     * @param FileInterface $entity
     * @param \SplFileInfo  $file
     */
    private function load(FileInterface $entity, \SplFileInfo $file)
    {
        if (null === $entity->getComputedPath() || $entity->getComputedPath() !== $file->getRealPath()) {
            $entity->setFilename($file->getFilename());
            $entity->setSize($file->getSize());
            $entity->setExtension($file->getExtension());

            // Improve data with uploadedfile details
            if ($file instanceof UploadedFile) {
                $entity->setFilename($file->getClientOriginalName());
                $entity->setExtension($file->guessExtension());
                $entity->setMime($file->getClientMimeType());
            } elseif (function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $entity->setMime(finfo_file($finfo, $file->getRealPath()));
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
                $this->load($entity, $file);

                $mdt = $args->getEntityManager()->getClassMetadata(get_class($entity));
                $args->getEntityManager()->getUnitOfWork()->computeChangeSet($mdt, $entity);
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
                if ($file->getRealPath() !== $entity->getComputedPath()) {
                    $s = DIRECTORY_SEPARATOR;

                    // Save file in different folders according to there class
                    $class = null;
                    if ($this->useClassDiscriminator) {
                        $class = str_replace('\\', $s, mb_strtolower(get_class($entity)));
                        if (false !== $pos = mb_strrpos($class, $s)) {
                            $class = mb_strcut($class, 1 + $pos);
                        }
                    }

                    // Create a tree
                    $tree = $entity->getId();
                    if (0 < $this->depth) {
                        $tree = implode($s, str_split(str_pad($tree, $this->depth, '0', STR_PAD_LEFT), 1));
                    }

                    // Create a path, and save it
                    $path = sprintf(
                        '%s%s%s%s%s.%s',
                        $entity instanceof FileReadableInterface ? ($this->kernelRootDir . $s . '..' . $s . 'web' . $this->webPath) : $this->securedPath,
                        $s,
                        true === $this->useEnvDiscriminator ? $this->env . $s : '',
                        null === $class ? '' : $class . $s,
                        $tree,
                        $entity->getExtension()
                    );
                    $this->logger->debug(__METHOD__ . ' : path = ' . $path);
                    try {
                        $this->fs->mkdir(pathinfo($path, PATHINFO_DIRNAME));
                        $this->fs->rename($file->getRealPath(), $path, true);

                        // Update
                        $entity
                            ->setFile(null)
                            ->setComputedPath(realpath($path));

                        // If this file is readable, save its URI
                        if ($entity instanceof FileReadableInterface) {
                            $uri = sprintf(
                                '%s/%s%s%s.%s',
                                $this->webPath,
                                $this->useEnvDiscriminator ? $this->env . '/' : '',
                                null === $class ? '' : $class . $s,
                                $tree,
                                $entity->getExtension()
                            );
                            $this->logger->debug(__METHOD__ . ' : uri = ' . $uri);
                            $entity->setComputedUri($uri);
                        }

                        // Save
                        $this->logger->debug(__METHOD__ . ' : saving in database');
                        $args->getEntityManager()->flush();
                        $this->logger->debug(__METHOD__ . ' : saved');
                    } catch (IOException $e) {
                        $this->logger->error(__METHOD__ . ' filesystem error : ' . $e->getMessage());
                    } catch (\Exception $e) {
                        $this->logger->error(__METHOD__ . ' : ' . $e->getMessage());
                    }
                }
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->postLoadFile($args);
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
            if (null !== $path = $entity->getComputedPath()) {
                $this->fs->remove($path);
            }
        }
    }
}
