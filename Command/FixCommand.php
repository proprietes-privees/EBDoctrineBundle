<?php

namespace EB\DoctrineBundle\Command;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use EB\DoctrineBundle\Entity\FileInterface;
use EB\DoctrineBundle\Entity\SlugInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FixCommand
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class FixCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('eb:doctrine:fix')
            ->setDescription('Fix database URI and SLUG.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $stringService = $this->getContainer()->get('eb_string');

        /** @var ClassMetadata[] $metadatas */
        $metadatas = $em->getMetadataFactory()->getAllMetadata();
        foreach ($metadatas as $metadata) {
            $ref = $metadata->getReflectionClass();

            // Fix slugs
            if ($ref->implementsInterface(SlugInterface::class)) {
                /** @var SlugInterface[] $entities */
                $entities = $em->getRepository($metadata->getName())->findAll();
                foreach ($entities as $entity) {
                    $slug = $stringService->slug($entity->getStringToSlug());
                    if ($slug === $entity->getSlug()) {
                        $output->writeln(sprintf('<info>Slug valid for %s</info> : %s', $entity, $slug));
                    } else {
                        $output->writeln(sprintf('<error>Fixing slug for %s</error> : %s', $entity, $slug));
                    }
                    $entity->setSlug($slug);
                }
            }

            // Fix md5
            if ($ref->implementsInterface(FileInterface::class)) {
                /** @var FileInterface[] $entities */
                $entities = $em->getRepository($metadata->getName())->findAll();
                foreach ($entities as $entity) {
                    if (null !== $path = $entity->getPath()) {
                        if (file_exists($path) && is_readable($path)) {
                            if (false !== $md5 = md5_file($path)) {
                                $entity->setMd5($md5);
                                $output->writeln(sprintf('<info>%s[[%u]] MD5 set for "%s"</info>', get_class($entity), $entity->getId(), $path));
                            } else {
                                $output->writeln(sprintf('<error>%s[[%u]] MD5 cannot be found for "%s"</error>', get_class($entity), $entity->getId(), $path));
                            }
                        } else {
                            $output->writeln(sprintf('<error>%s[%u] "%s" does not exist</error>', get_class($entity), $entity->getId(), $path));
                        }
                    } else {
                        $output->writeln(sprintf('<error>No path found found %s[%u]</error>', get_class($entity), $entity->getId()));
                    }
                }
            }
        }
        $em->flush();

        return 0;
    }
}
