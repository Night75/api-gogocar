<?php

namespace AppBundle\Manager;

use AppBundle\Util\ClassUtils;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Manager\Exception\InvalidArgumentException;
use AppBundle\Manager\Exception\ResourceNotFoundException;
use Gogocar\Dto\Model\BaseModel;

abstract class AbstractManager
{
    /**
     * @var ObjectManager
     */
    protected $om;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $repository;

    /**
     * @param ObjectManager $om
     * @param $class
     */
    public function __construct(ObjectManager $om)
    {
        $this->om         = $om;
        $this->repository = $om->getRepository($this->getEntityClass());
    }

    /**
     * @param integer $id
     *
     * @return object
     *
     * @throws ResourceNotFoundException When the resource was not found
     */
    public function get($id)
    {
        $resource = $this->repository->find($id);

        if ($resource === null) {
            throw new ResourceNotFoundException(sprintf(
                    'The "%s" with id "%s" was not found',
                    $this->getEntityClass(),
                    $id
                ));
        }

        return $resource;
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $resource = $this->om->getReference($this->getEntityClass(), ['id' => $id]);
        $this->om->remove($resource);
    }

    /**
     * @param \stdClass $resource
     */
    public function save(BaseModel $resource)
    {
        $entityClass = $this->getEntityClass();

        $entity = ClassUtils::isEntity($resource) ? $resource :
            ClassUtils::buildObjectFromAnother($resource, $entityClass);

        if (false === $entity instanceof $entityClass) {
            throw new InvalidArgumentException(sprintf(
                    'The resource should be an instance of "%s", "%s" given.',
                    $entityClass,
                    get_class($entity)
                ));
        }

        if ($this->om->contains($entity)) {
            throw new InvalidArgumentException('This entity is already managed.');
        }

        $this->om->persist($entity);
        $this->om->flush();

        return $entity;
    }

    /**
     * Return the repository for the default class.
     *
     * @return mixed
     */
    abstract protected function getEntityClass();
}
