<?php

namespace AppBundle\Manager;

use AppBundle\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Manager\Exception\InvalidArgumentException;
use AppBundle\Manager\Exception\ResourceNotFoundException;
use Gogocar\Dto\Model\BaseModel;

abstract class AbstractManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $repository;

    /**
     * @param EntityManagerInterface $em
     * @param $class
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em         = $em;
        $this->repository = $em->getRepository($this->getEntityClass());
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
        // $resource = $this->em->getReference($this->getEntityClass(), ['id' => $id]);
        $resource = $this->get($id);
        $this->em->remove($resource);
        $this->em->flush();
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

        if ($this->em->contains($entity)) {
            throw new InvalidArgumentException('This entity is already managed.');
        }

        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    /**
     * Return the repository for the default class.
     *
     * @return mixed
     */
    abstract protected function getEntityClass();
}
