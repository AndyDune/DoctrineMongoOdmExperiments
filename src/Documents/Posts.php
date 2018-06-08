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


namespace AndyDune\DoctrineMongoOdmExperiments\Documents;

/** @ODM\Document(collection="posts") */
class Posts
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field */
    private $title;

    /** @ODM\Field */
    private $body;

    /** @ODM\ReferenceOne(targetDocument="User") */
    private $user;
}