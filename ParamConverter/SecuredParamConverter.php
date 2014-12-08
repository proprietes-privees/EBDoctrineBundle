<?php

namespace EB\DoctrineBundle\ParamConverter;

use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DoctrineParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * Class SecuredParamConverter
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class SecuredParamConverter extends DoctrineParamConverter
{
    /**
     * @var AuthorizationChecker
     */
    private $authorizationChecker;

    /**
     * @param ManagerRegistry      $registry             Manager
     * @param AuthorizationChecker $authorizationChecker Authorization checker
     */
    public function __construct(ManagerRegistry $registry, AuthorizationChecker $authorizationChecker)
    {
        parent::__construct($registry);
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        // Doctrine param converter must apply
        if (true === $done = parent::apply($request, $configuration)) {
            $options = $configuration->getOptions();

            // If there is a role in options ...
            if (true === array_key_exists('role', $options)) {
                // Access control
                if (false === $this->authorizationChecker->isGranted($options['role'], $request->attributes->get($configuration->getName()))) {
                    throw new AccessDeniedHttpException();
                }
            }

            // If there is multiple roles in options ...
            if (true === array_key_exists('roles', $options)) {
                foreach ($options['roles'] as $role) {
                    if ($this->authorizationChecker->isGranted($role, $request->attributes->get($configuration->getName()))) {
                        return true;
                    }
                }

                throw new AccessDeniedHttpException();
            }
        }

        return $done;
    }
}
