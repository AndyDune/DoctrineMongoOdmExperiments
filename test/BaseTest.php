<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 04.06.2018                            |
 * -----------------------------------------------
 *
 */


namespace AndyDune\DoctrineMongoOdmExperimentsTest;
use AndyDune\DoctrineMongoOdmExperiments\Documents\User;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use MongoDB\Client;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{

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
        $config->setPersistentCollectionDir(__DIR__ . '/../../../../PersistentCollections');
        $config->setPersistentCollectionNamespace('PersistentCollections');

        $config->setDefaultDB(DOCTRINE_MONGODB_DATABASE);
        $config->setMetadataDriverImpl(AnnotationDriver::create(__DIR__   . '/../lib/Documents'));

        $dm = DocumentManager::create(new Client(DOCTRINE_MONGODB_SERVER), $config);
        return $dm;
    }

    public function testUserDocument()
    {
        $user = new User();
        $user->setName('Andrey');
        $user->setEmail('info@rznw.ru');

        $dm = $this->getConnection();
        $dm->getClient()->dropDatabase(DOCTRINE_MONGODB_DATABASE);

        $dm->persist($user);
        $dm->flush();

        $user = $dm->getRepository(User::class)->findOneBy(array('name' => 'Andrey'));
        $this->assertEquals('Andrey', $user->getName());


    }
}