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
use AndyDune\DoctrineMongoOdmExperiments\Documents\Data\Article;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use AndyDune\DoctrineMongoOdmExperiments\Documents\Data\Posts as DataPosts;
use Doctrine\ODM\MongoDB\PersistentCollection;

/** @ODM\Document(collection="users", repositoryClass="AndyDune\DoctrineMongoOdmExperiments\Repository\UserRepository") */
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


    /*
     *
     * The storeAs option has the following possible values:
            dbRefWithDb: Uses a DBRef with $ref, $id, and $db fields
            dbRef: Uses a DBRef with $ref and $id (this is the default)
            ref: Uses a custom embedded object with an id field
            id: Uses the identifier of the referenced object
    Up until 2.0 storeAs=dbRefWithDb was the default setting. If you have data in the old format, you should add storeAs=dbRefWithDb to all your references,
     or update the database references (deleting the $db field) as storeAs=dbRef is the new default setting.
     *
     */

    /** @ODM\ReferenceMany(targetDocument="AndyDune\DoctrineMongoOdmExperiments\Documents\Data\Posts", storeAs="dbRef", cascade="all", orphanRemoval=true) */
    private $posts = [];

    /** @ODM\ReferenceMany(targetDocument="AndyDune\DoctrineMongoOdmExperiments\Documents\Data\Article",
          cascade="all",
          mappedBy="user") */
    private $articles = [];


    /** @ODM\ReferenceMany(targetDocument="UserTestDataOne", mappedBy="user") */
    private $testDataOne;


    /** @ODM\ReferenceOne(targetDocument="User", storeAs="dbRef") */
    private $wife;

    /** @ODM\ReferenceOne(targetDocument="Home", storeAs="dbRef") */
    private $home;

    /** @ODM\ReferenceOne(targetDocument="Home", cascade={"persist", "remove"}, storeAs="id")*/
    private $homeHard;

    public function __construct()
    {
        $this->testDataOne = new ArrayCollection();
    }

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
     * @param DataPosts $posts
     */
    public function setPost(DataPosts $posts): void
    {
        $this->posts = [$posts];
    }

    /**
     * @param DataPosts $post
     */
    public function addPost(DataPosts $post): void
    {
        $this->posts[] = $post;
    }


    /**
     * @param Article $article
     */
    public function setArticle(Article $article): void
    {
        $this->articles = [$article];
    }

    /**
     * @param Article $post
     */
    public function addArticle(Article $article): void
    {
        $this->articles[] = $article;
    }

    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @return PersistentCollection
     */
    public function getTestDataOne()
    {
        return $this->testDataOne;
    }

    /**
     * @param mixed $testData
     */
    public function setTestDataOne($testData): void
    {
        $this->testDataOne = $testData;
    }

    /**
     * @param mixed $testData
     */
    public function addTestDataOne($testData): void
    {
        $this->testDataOne[] = $testData;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }



}