<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 27.06.2018                            |
 * -----------------------------------------------
 *
 */


namespace AndyDune\DoctrineMongoOdmExperiments\Documents;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;


/** @ODM\Document(collection="user_test_data_one") */
class UserTestDataOne
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $name;

    /** @ODM\ReferenceOne(targetDocument="User", inversedBy="testDataOne", storeAs="dbRef") */
    protected $user;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

}