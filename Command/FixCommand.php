<?php

namespace EB\DoctrineBundle\Command;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use EB\DoctrineBundle\Entity\SlugInterface;
use EB\DoctrineBundle\Entity\UriInterface;

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
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        /** @var ClassMetadata[] $metadatas */
        $metadatas = $em->getMetadataFactory()->getAllMetadata();
        foreach ($metadatas as $metadata) {
            $ref = $metadata->getReflectionClass();

            // Fix uris
            if ($ref->implementsInterface('EB\DoctrineBundle\Entity\UriInterface')) {
                /** @var UriInterface[] $entities */
                $entities = $em->getRepository($metadata->getName())->findAll();
                foreach ($entities as $entity) {
                    $output->writeln(sprintf('Fixing "uri" for "%s" (%s)', $entity, $metadata->getName()));
                    $entity->setUri(uniqid());
                }
            }

            // Fix slugs
            if ($ref->implementsInterface('EB\DoctrineBundle\Entity\SlugInterface')) {
                /** @var SlugInterface[] $entities */
                $entities = $em->getRepository($metadata->getName())->findAll();
                foreach ($entities as $entity) {
                    $entity->setSlug(uniqid());
                    $output->writeln(sprintf('Fixing "slug" for "%s" (%s)', $entity, $metadata->getName()));
                }
            }
        }
        $em->flush();

        return 0;
    }
}
