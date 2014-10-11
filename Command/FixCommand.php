<?php

namespace EB\DoctrineBundle\Command;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
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
            if ($ref->implementsInterface('EB\DoctrineBundle\Entity\SlugInterface')) {
                /** @var SlugInterface[] $entities */
                $entities = $em->getRepository($metadata->getName())->findAll();
                foreach ($entities as $entity) {
                    $slug = $stringService->slug($entity->getStringToSlug());
                    if($slug === $entity->getSlug()) {
                        $output->writeln(sprintf('<info>Slug valid for %s</info> : %s', $entity, $slug));
                    }else{
                        $output->writeln(sprintf('<error>Fixing slug for %s</error> : %s', $entity, $slug));
                    }
                    $entity->setSlug($slug);
                }
            }
        }
        $em->flush();

        return 0;
    }
}
