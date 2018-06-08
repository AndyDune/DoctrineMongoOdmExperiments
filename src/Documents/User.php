<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 05.06.2018                            |
 * -----------------------------------------------
 *
The valid values are:
    all - cascade all operations by default.
    detach - cascade detach operation to referenced documents.
    merge - cascade merge operation to referenced documents.
    refresh - cascade refresh operation to referenced documents.
    remove - cascade remove operation to referenced documents.
    persist - cascade persist operation to referenced documents. *
 */

namespace AndyDune\DoctrineMongoOdmExperiments\Documents;

use AndyDune\DateTime\DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document(collection="users") */
class User
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $name;

    /** @ODM\Field(type="date", name="datetime_register") */
    private $datetimeRegister;

    /** @ODM\Field(type="date_andydune", name="datetime_andydune") */
    private $datetimeAndyDune;

    /** @var @ODM\Field(type="int") */
    private $count;

    /** @ODM\Field(type="collection") */
    private $roles;

    /** @ODM\Field(type="string") */
    private $email;

    /** @ODM\ReferenceMany(targetDocument="Posts", cascade="all") */
    private $posts = array();

    /** @ODM\ReferenceOne(targetDocument="User") */
    private $wife;

    /** @ODM\ReferenceOne(targetDocument="Home") */
    private $home;

    /** @ODM\ReferenceOne(targetDocument="Home", cascade={"persist", "remove"}, storeAs="id")*/
    private $homeHard;

    /**
     * @return mixed
     */
    public function getHomeHard()
    {
        return $this->homeHard;
    }

    /**
     * @param mixed $homeHard
     */
    public function setHomeHard($homeHard): void
    {
        $this->homeHard = $homeHard;
    }


    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param mixed $count
     */
    public function setCount($count): void
    {
        $this->count = $count;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return mixed
     */
    public function getWife()
    {
        return $this->wife;
    }

    /**
     * @param mixed $wife
     */
    public function setWife($wife): void
    {
        $this->wife = $wife;
    }

    /**
     * @return mixed
     */
    public function getHome()
    {
        return $this->home;
    }

    /**
     * @param mixed $home
     */
    public function setHome($home): void
    {
        $this->home = $home;
    }




    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return DateTime
     */
    public function getDatetimeAndyDune() : DateTime
    {
        return $this->datetimeAndyDune;
    }

    /**
     * @param mixed $datetimeAndyDune
     */
    public function setDatetimeAndyDune($datetimeAndyDune): void
    {
        $this->datetimeAndyDune = $datetimeAndyDune;
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
    public function getDatetimeRegister()
    {
        return $this->datetimeRegister;
    }

    /**
     * @param mixed $datetimeRegister
     */
    public function setDatetimeRegister($datetimeRegister = null): User
    {
        if (!$datetimeRegister) {
            $datetimeRegister = time();
        }
        $this->datetimeRegister = $datetimeRegister;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param mixed $posts
     */
    public function setPosts($posts): void
    {
        $this->posts = $posts;
    }



}