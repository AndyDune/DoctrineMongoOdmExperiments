<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 08.06.2018                            |
 * -----------------------------------------------
 *
 */


namespace AndyDune\DoctrineMongoOdmExperiments\Documents\Data;
use AndyDune\DoctrineMongoOdmExperiments\Documents\User;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="posts")
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField("type")
 * @ODM\DiscriminatorMap({"posts"="Posts", "articles"="Article"})
 * @ODM\DefaultDiscriminatorValue("posts")
 */
class Posts
{
    /** @ODM\Id */
    protected $id;

    /** @ODM\Field */
    protected $title;

    /** @ODM\Field */
    protected $body;

    /** @ODM\ReferenceOne(targetDocument="User", storeAs="dbRef") */
    protected $user;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
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
    public function setUser(User $user): void
    {
        $this->user = $user;
    }


}