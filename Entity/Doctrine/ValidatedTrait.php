<?php

namespace EB\DoctrineBundle\Entity\Doctrine;

/**
 * Class ValidatedTrait
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
trait ValidatedTrait
{
    /**
     * @var null|\DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $validated;

    /**
     * @var null|\DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $invalidated;

    /**
     * Get Invalidated
     *
     * @return null|\DateTime
     */
    public function getInvalidated()
    {
        return $this->invalidated;
    }

    /**
     * Set Invalidated
     *
     * @param null|\DateTime $invalidated Invalidated
     *
     * @return $this
     */
    public function setInvalidated(\DateTime $invalidated = null)
    {
        $this->invalidated = $invalidated;
        if (null !== $invalidated) {
            $this->setValidated(null);
        }

        return $this;
    }

    /**
     * Get Validated
     *
     * @return null|\DateTime
     */
    public function getValidated()
    {
        return $this->validated;
    }

    /**
     * Set Validated
     *
     * @param null|\DateTime $validated Validated
     *
     * @return $this
     */
    public function setValidated(\DateTime $validated = null)
    {
        $this->validated = $validated;
        if (null !== $validated) {
            $this->setInvalidated(null);
        }

        return $this;
    }

    /**
     * Has been validated
     *
     * @return bool
     */
    public function hasBeenValidated()
    {
        return null !== $this->getValidated() || null !== $this->getInvalidated();
    }

    /**
     * Invalidate
     *
     * @return $this
     */
    public function invalidate()
    {
        $this->setInvalidated(new \DateTime());

        return $this;
    }

    /**
     * Validity
     *
     * @return bool
     */
    public function isValid()
    {
        if (null !== $this->getValidated()) {
            return true;
        }

        return false;
    }

    /**
     * Validate
     *
     * @return $this
     */
    public function validate()
    {
        $this->setValidated(new \DateTime());

        return $this;
    }
}
