<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 07.07.2018                            |
 * -----------------------------------------------
 *
 */


namespace AndyDune\DoctrineMongoOdmExperiments\Documents;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;


/**
 * @ODM\Document(collection={
 *   "name"="log",
 *   "capped"=true,
 *   "size"=100000,
 *   "max"=1000
 * })
 */
class Log
{
    /** @ODM\Id */
    public $id;

    /** @ODM\Field(type="string") */
    public $name;

    /** @ODM\Field(type="date") */
    public $dataTime;
}