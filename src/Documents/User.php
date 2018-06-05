<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 05.06.2018                            |
 * -----------------------------------------------
 *
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


    /** @ODM\Field(type="collection") */
    private $roles;

    /** @ODM\Field(type="string") */
    private $email;

    /** @ODM\ReferenceMany(targetDocument="BlogPost", cascade="all") */
    private $posts = array();

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