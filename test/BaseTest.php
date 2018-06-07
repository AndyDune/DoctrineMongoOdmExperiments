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
use AndyDune\DateTime\DateTime;
use AndyDune\DoctrineMongoOdmExperiments\Documents\User;
use AndyDune\DoctrineMongoOdmExperiments\Types\DateAndyDune;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Doctrine\ODM\MongoDB\Types\Type;
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
        $config->setPersistentCollectionDir(__DIR__ . '/PersistentCollections');
        $config->setPersistentCollectionNamespace('PersistentCollections');

        $config->setDefaultDB(DOCTRINE_MONGODB_DATABASE);
        $config->setMetadataDriverImpl(AnnotationDriver::create(__DIR__   . '/../lib/Documents'));

        $dm = DocumentManager::create(new Connection(DOCTRINE_MONGODB_SERVER), $config);
        return $dm;
    }

    public function testUserDocument()
    {
        Type::addType('date_andydune', DateAndyDune::class);
        $user = new User();
        $user->setName('Andrey');
        $user->setEmail('info@rznw.ru');
        $user->setCount('23');

        $time = time();
        $user->setDatetimeRegister((new DateTime($time))->getValue());
        $user->setDatetimeAndyDune((new DateTime($time)));

        $dm = $this->getConnection();
        $dm->getConnection()->dropDatabase(DOCTRINE_MONGODB_DATABASE);

        $dm->persist($user);
        $dm->flush();
        $dm->clear();

        /** @var User $user */
        $user = $dm->getRepository(User::class)->findOneBy(array('name' => 'Andrey'));
        $this->assertEquals('Andrey', $user->getName());
        $this->assertEquals(23, $user->getCount());
        $this->assertEquals((new DateTime($time))->getValue(), $user->getDatetimeRegister());

        $time1 = time() + 100;
        $user->setDatetimeRegister($time1);

        $dm->flush();
        $dm->clear();

        $user = $dm->getRepository(User::class)->findOneBy(array('name' => 'Andrey1'));
        $this->assertEquals(null, $user);
        $user = $dm->getRepository(User::class)->findOneBy(array('name' => 'Andrey'));
        $this->assertEquals('Andrey', $user->getName());
        $this->assertEquals((new DateTime($time1))->getValue(), $user->getDatetimeRegister());
        $this->assertEquals((new DateTime($time))->getTimestamp(), $user->getDatetimeAndyDune()->getTimestamp());


        // @todo need type check
        $user = $dm->getRepository(User::class)->findOneBy(array('count' => '23'));
        $this->assertEquals(null, $user);
        $dm->clear();

        $user = $dm->getRepository(User::class)->findOneBy(array('count' => 23));
        $this->assertInstanceOf(User::class, $user);


        $query = $dm->createQueryBuilder(User::class);
        /**
         *
         */
        $result = $query->field('count')->equals(23)->find()->getQuery()->getSingleResult();


        $user = $dm->getRepository(User::class)->findOneBy(array('datetime_register' => (new DateTime($time1))->getValue()));
        $this->assertInstanceOf(User::class, $user);

        $user = $dm->getRepository(User::class)->findOneBy(array('datetime_andydune' => (new DateTime($time))));
        $this->assertInstanceOf(User::class, $user);


    }
}