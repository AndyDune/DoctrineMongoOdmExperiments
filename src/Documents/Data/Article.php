<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 12.06.2018                            |
 * -----------------------------------------------
 *
 *
 * https://www.doctrine-project.org/projects/doctrine-mongodb-odm/en/latest/reference/inheritance-mapping.html#inheritance-mapping
 *
 */


namespace AndyDune\DoctrineMongoOdmExperiments\Documents\Data;
use AndyDune\DoctrineMongoOdmExperiments\Documents\User;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;


/** @ODM\Document */
class Article extends Posts
{
    /** @ODM\ReferenceOne(targetDocument="AndyDune\DoctrineMongoOdmExperiments\Documents\User", inversedBy="articles", storeAs="dbRef") */
    protected $user;

}