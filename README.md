EBDoctrineBundle
================

Set of doctrine listeners.

EBFileBundle (merged)
=====================

## Configuration

```yaml
eb_doctrine:
  useEnvDiscriminator: false
  depth: 0
  path:
    web: '/somewhere/readable'
    secured: '%kernel.root_dir%/cache/somewhere/not/readable'
```

## Usage

  - Create an entity
  - Implement one of these interface or superclass:
    - EB\DoctrineBundle\Entity\FileInterface (secured, no web access)
    - EB\DoctrineBundle\Entity\FileReadableInterface (add web access)
  - Add an \SplFileInfo to your entity using "setFile" method
  - EB\DoctrineBundle\Entity\FileListener will automatically :
    - Save file in the file system
    - Save its name, extension and size
    - Add a path/uri to this entity
    - Delete file when entity is deleted

EBUserBundle (merged)
=====================

This bundle automates :
  - The generation of a salt
  - The generation of a user password
  - The persistence of last and current user login dates
  - The persistence of last password update date

# Salt
  - Your Salted entity has to inherit SaltedInterface
  - When the entity is persisted or updated, a new salt will be generated

# User
  - Your User entity has to inherit UserInterface.
  - When a new password is entered, the plain value has to be set in the rawPassword field.
  - When the entity is persisted or updated, and the rawPassword field is not empty, the listener will encode the password before the database persistence.

Add a listener in your User forms to be sure that Doctrine sees the actual changes in its managed entity. Something like :

```php
<?php

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $user = $event->getData();
            if ($user instanceof User && null !== $user->getRawPassword()) {
                $user->setPassword('dirty');
            }
        });
    }
}
```
