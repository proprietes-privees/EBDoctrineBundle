EBDoctrineBundle
================

Set of doctrine listeners.

EBFileBundle
============

## Configuration

```yaml
eb_file:
  useEnvDiscriminator: false
  depth: 0
  path:
    web: '/somewhere/readable'
    secured: '%kernel.root_dir%/cache/somewhere/not/readable'
```

## Usage

  - Create an entity
  - Implement one of these interface or superclass:
    - EB\FileBundle\Entity\FileInterface (secured, no web access)
    - EB\FileBundle\Entity\FileReadableInterface (add web access)
  - Add an \SplFileInfo to your entity using "setFile" method
  - EB\FileBundle\Entity\FileListener will automatically save this file
    - Save file in the file system
    - Save its name, extension and size
    - Add a path/uri to this entity
    - Delete file when entity is deleted
