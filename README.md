# EBDoctrineBundle

Set of Doctrine listeners.

## Configuration

    eb_doctrine:

        # Wether env is used in paths
        useEnvDiscriminator:  true # Example: true

        # Wether class is used in paths
        useClassDiscriminator:  true # Example: true

        # File tree depth
        depth:                0 # Example: 5
        path:

            # Web file path.
            web:                  /files # Example: /files

            # Secured file path.
            secured:              '%kernel.root_dir%/cache/%kernel.environment%/files' # Example: /var/my-data

## Usage

### Track ``created`` date

  - Implement ``EB\DoctrineBundle\Entity\CreatedInterface``
  - Use ``EB\DoctrineBundle\Entity\Doctrine\CreatedTrait``
  - The creation date will always be saved in the ``created`` field

### Track ``updated`` date

  - Implement ``EB\DoctrineBundle\Entity\UpdatedInterface``
  - Use ``EB\DoctrineBundle\Entity\Doctrine\UpdatedTrait``
  - The update date will always be saved in the ``updated`` field
  - This date will be ``null`` when this entity has never been updated

### Generate ``slug``

  - Implement ``EB\DoctrineBundle\Entity\SlugInterface``
  - Use ``EB\DoctrineBundle\Entity\Doctrine\SlugTrait``
  - Define your own ``getStringToSlug`` method telling the listener what string needs to be cleaned
  - The slug will always be saved in the ``slug`` field
  - The command ``eb:doctrine:fix`` is able to re-evaluate each slug field in your database

### Generate ``salt``

  - Implement ``EB\DoctrineBundle\Entity\SaltInterface``
  - Use ``EB\DoctrineBundle\Entity\Doctrine\SaltTrait``
  - The salt will always be saved in the ``salt`` field
  - Currently the salt is a sha512 hash (see ``EB\DoctrineBundle\Salt\SaltTrait``)

### Save a file with an entity

  - Implement one of these interfaces :
    - ``EB\DoctrineBundle\Entity\FileInterface`` (if you don't want a direct access via your webserver)
    - ``EB\DoctrineBundle\Entity\FileReadableInterface`` (stored in your web folder, add an URI path)
    - ``EB\DoctrineBundle\Entity\FileVersionableInterface`` (track file version)
  - Use those traits :
    - ``EB\DoctrineBundle\Entity\Doctrine\FileTrait``
    - ``EB\DoctrineBundle\Entity\Doctrine\FileReadableTrait``
    - ``EB\DoctrineBundle\Entity\Doctrine\FileVersionableTrait``
  - Add an ``\SplFileInfo`` or an ``UploadedFile`` to your entity using ``setFile`` method
  - ``EB\DoctrineBundle\Entity\FileListener`` will automatically :
    - Save this file in the filesystem (using the entity ID and your configuration)
    - Save its ``filename``, ``extension``, ``size`` and ``mime``
    - Save a ``uniqid`` (this is a trick to create mapped updates when using forms with unmapped ``file`` field)
    - Add a ``path``, the current realpath of the file in the filesystem
    - Add an ``uri``, from the web directory
    - Increase ``version`` if necessary
    - Delete the file in the filesystem when the entity is removed

### Deal with users

  - Implement one of these interfaces :
    - ``EB\DoctrineBundle\Entity\UserInterface``
    - ``EB\DoctrineBundle\Entity\UserLoginInterface``
    - ``EB\DoctrineBundle\Entity\UserPasswordDateInterface``
  - Use those traits :
    - ``EB\DoctrineBundle\Entity\Doctrine\UserTrait``
    - ``EB\DoctrineBundle\Entity\Doctrine\UserAdvancedTrait``
    - ``EB\DoctrineBundle\Entity\Doctrine\UserLoginTrait``
    - ``EB\DoctrineBundle\Entity\Doctrine\UserPasswordDateTrait``
  - The ``rawPassword`` will always be encoded into a ``password`` when saved
  - Add all required fields and methods to create a user
  - Track current and previous login dates
  - Track password update date
