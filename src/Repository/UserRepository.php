<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 28.07.2018                            |
 * -----------------------------------------------
 *
 */

namespace AndyDune\DoctrineMongoOdmExperiments\Repository;
use AndyDune\DateTime\DateTime;
use AndyDune\DoctrineMongoOdmExperiments\Documents\User;
use Doctrine\ODM\MongoDB\DocumentRepository;
use MongoDB\BSON\UTCDateTime;

class UserRepository extends DocumentRepository
{
    /**
     * @param $name
     * @return User|null
     */
    public function getUserWithName($name)
    {
        return $this->findOneBy(array('name' => $name));
    }

    /**
     * @param $date
     * @return []
     */
    public function getUsersAfterAndyDuneDate(DateTime $date)
    {
        $date = $date->getValue();
        return $this->findBy(['datetime_andydune' => ['$gt' => $date]]);
    }

}