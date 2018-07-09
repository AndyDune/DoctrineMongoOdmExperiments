<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 09.07.2018                            |
 * -----------------------------------------------
 *
 */


namespace AndyDune\DoctrineMongoOdmExperimentsTest;
use AndyDune\DateTime\DateTime;
use AndyDune\DoctrineMongoOdmExperiments\Documents\Data\Article;
use AndyDune\DoctrineMongoOdmExperiments\Documents\Data\Posts;
use AndyDune\DoctrineMongoOdmExperiments\Documents\Home;
use AndyDune\DoctrineMongoOdmExperiments\Documents\Log;
use AndyDune\DoctrineMongoOdmExperiments\Documents\User;
use AndyDune\DoctrineMongoOdmExperiments\Documents\UserTestDataOne;
use AndyDune\DoctrineMongoOdmExperiments\Types\DateAndyDune;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Doctrine\ODM\MongoDB\PersistentCollection;
use Doctrine\ODM\MongoDB\Persisters\DocumentPersister;
use Doctrine\ODM\MongoDB\Types\Type;
use PHPUnit\Framework\TestCase;



class QueryBuilderTest extends TestCase
{

    public function setUp()
    {

    }

    static public function setUpBeforeClass()
    {
        //Type::addType('date_andydune', DateAndyDune::class);
    }

    protected function getConnection()
    {
        $config = new Configuration();
        $config->setProxyDir(__DIR__ . '/Proxies');
        $config->setProxyNamespace('Proxies');
        $config->setHydratorDir(__DIR__ . '/Hydrators');
        $config->setHydratorNamespace('Hydrators');

        /*
         * Рассмотреть зачем сие:
         */
        $config->setPersistentCollectionDir(__DIR__ . '/PersistentCollections');
        $config->setPersistentCollectionNamespace('PersistentCollections');

        $config->setDefaultDB(DOCTRINE_MONGODB_DATABASE);
        $config->addDocumentNamespace('Data\Posts', 'AndyDune\DoctrineMongoOdmExperiments\Documents\Data\Posts');
        $config->setMetadataDriverImpl(AnnotationDriver::create(__DIR__ . '/../lib/Documents'));

        $dm = DocumentManager::create(new Connection(DOCTRINE_MONGODB_SERVER), $config);
        return $dm;
    }


    public function testSingleResult()
    {

        $user = new User();
        $user->setName('Andrey');
        $user->setEmail('info@rznw.ru');
        $user->setCount('23');

        $time = time();

        $dm = $this->getConnection();
        $dm->getConnection()->selectCollection(DOCTRINE_MONGODB_DATABASE, 'users')->getMongoCollection()->getCollection()->deleteMany([]);

        $dm->persist($user);
        $dm->flush();

        $qb = $dm->createQueryBuilder(User::class);
        $query = $qb->field('name')->equals('No Andrey')
            ->getQuery();
        $result = $query->getSingleResult();
        $this->assertEquals(null, $result);


        $qb = $dm->createQueryBuilder(User::class);
        $query = $qb->field('name')->equals('Andrey')
            ->getQuery();
        $result = $query->getSingleResult();
        $this->assertInstanceOf(User::class, $result);
    }

}