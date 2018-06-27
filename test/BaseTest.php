<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 04.06.2018                            |
 * -----------------------------------------------
 *
 *
 * Read to help:
 * https://phpunit.readthedocs.io/en/7.1/installation.html
 */


namespace AndyDune\DoctrineMongoOdmExperimentsTest;

use AndyDune\DateTime\DateTime;
use AndyDune\DoctrineMongoOdmExperiments\Documents\Data\Article;
use AndyDune\DoctrineMongoOdmExperiments\Documents\Data\Posts;
use AndyDune\DoctrineMongoOdmExperiments\Documents\Home;
use AndyDune\DoctrineMongoOdmExperiments\Documents\User;
use AndyDune\DoctrineMongoOdmExperiments\Documents\UserTestDataOne;
use AndyDune\DoctrineMongoOdmExperiments\Types\DateAndyDune;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Doctrine\ODM\MongoDB\PersistentCollection;
use Doctrine\ODM\MongoDB\Types\Type;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{

    public function setUp()
    {

    }

    static public function setUpBeforeClass()
    {
        Type::addType('date_andydune', DateAndyDune::class);
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

    public function testUserDocument()
    {
        $user = new User();
        $user->setName('Andrey');
        $user->setEmail('info@rznw.ru');
        $user->setCount('23');

        $time = time();
        $user->setDatetimeRegister((new DateTime($time))->getValue());
        $user->setDatetimeAndyDune((new DateTime($time)));

        $dm = $this->getConnection();
//        $dm->getConnection()->dropDatabase(DOCTRINE_MONGODB_DATABASE);
        $dm->getConnection()->selectCollection(DOCTRINE_MONGODB_DATABASE, 'users')->getMongoCollection()->getCollection()->deleteMany([]);

        $dm->persist($user);
        $dm->flush();

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

        $user = $dm->getRepository(User::class)->findOneBy(array('datetime_andydune' => (new DateTime($time))->getValue()));
        $this->assertInstanceOf(User::class, $user);
        //$this->assertEquals(null, $user);
    }

    public function _testUserReference()
    {
        $dm = $this->getConnection();
        $dm->getConnection()->selectCollection(DOCTRINE_MONGODB_DATABASE, 'users')->getMongoCollection()->getCollection()->deleteMany([]);
        $dm->getConnection()->selectCollection(DOCTRINE_MONGODB_DATABASE, 'posts')->getMongoCollection()->getCollection()->deleteMany([]);
        $dm->getConnection()->selectCollection(DOCTRINE_MONGODB_DATABASE, 'homes')->getMongoCollection()->getCollection()->deleteMany([]);

        $user = new User();
        $dm->persist($user);
        $user->setName('Andrey');
        $user->setEmail('info@rznw.ru');
        $user->setCount('23');

        $home = new Home();
        $dm->persist($home);
        $home->setName('Родина');

        $homeH = new Home();
        $homeH->setName('Ночевка');

        $user->setHomeHard($homeH);
        $user->setHome($home);

        $dm->flush();
        $dm->clear();

        $user = $dm->getRepository(User::class)->findOneBy(array('name' => 'Andrey'));
        $this->assertEquals('Andrey', $user->getName());

        $home = $user->getHome();
        $this->assertEquals('Родина', $home->getName());
        $homeH = $user->getHomeHard();
        $this->assertEquals('Ночевка', $homeH->getName());

        $dm->remove($user);
        $dm->flush();

        $user = $dm->getRepository(User::class)->findOneBy(array('name' => 'Andrey'));
        $this->assertEquals(null, $user);

        $home = $dm->getRepository(Home::class)->findOneBy(array('name' => 'Родина'));
        $this->assertInstanceOf(Home::class, $home);

        $user = new User();
        $dm->persist($user);
        $user->setName('Andrey');
        $user->setEmail('info@rznw.ru');

        $post = new Posts();
        $post->setTitle('Статья 1');
        $user->setPost($post);

        $post = new Posts();
        $post->setTitle('Статья 2');
        $user->setPost($post);

        $dm->flush();
        $dm->clear();

        $user = $dm->getRepository(Posts::class)->findOneBy(array('title' => 'Статья 1'));
        $this->assertEquals(null, $user);

        $home = $dm->getRepository(Posts::class)->findOneBy(array('title' => 'Статья 2'));
        $this->assertInstanceOf(Posts::class, $home);

        /** @var User $user */
        $user = $dm->getRepository(User::class)->findOneBy(array('name' => 'Andrey'));
        $post = new Posts();
        $post->setTitle('Статья 1');
        $user->addPost($post);

        $dm->flush();
        $dm->clear();

        $home = $dm->getRepository(Posts::class)->findOneBy(array('title' => 'Статья 1'));
        $this->assertInstanceOf(Posts::class, $home);
        $home = $dm->getRepository(Posts::class)->findOneBy(array('title' => 'Статья 2'));
        $this->assertInstanceOf(Posts::class, $home);

        $user = $dm->getRepository(User::class)->findOneBy(array('name' => 'Andrey'));
        $dm->remove($user);
        $dm->flush();

        $user = $dm->getRepository(Posts::class)->findOneBy(array('title' => 'Статья 1'));
        $this->assertEquals(null, $user);

        $home = $dm->getRepository(Posts::class)->findOneBy(array('title' => 'Статья 2'));
        $this->assertEquals(null, $home);
    }

    public function _testInheritanceMapping()
    {
        $dm = $this->getConnection();
        $dm->getConnection()->selectCollection(DOCTRINE_MONGODB_DATABASE, 'users')->getMongoCollection()->getCollection()->deleteMany([]);
        $dm->getConnection()->selectCollection(DOCTRINE_MONGODB_DATABASE, 'posts')->getMongoCollection()->getCollection()->deleteMany([]);
        $dm->getConnection()->selectCollection(DOCTRINE_MONGODB_DATABASE, 'homes')->getMongoCollection()->getCollection()->deleteMany([]);

        $user = new User();
        $dm->persist($user);
        $user->setName('Andrey');
        $user->setEmail('info@rznw.ru');
        $user->setCount('23');

        $dm->flush();

        $post = new Posts();
        $post->setUser($user);
        $post->setTitle('Пост 1');
        $user->setPost($post);

        $article = new Article();
        $article->setTitle('Статья 1');
        $article->setUser($user);
        $user->setArticle($article);

        $dm->flush();

        $home = $dm->getRepository(Posts::class)->findOneBy(array('title' => 'Пост 1'));
        $this->assertInstanceOf(Posts::class, $home);
        $home = $dm->getRepository(Article::class)->findOneBy(array('title' => 'Пост 1'));
        $this->assertEquals(null, $home);

        $home = $dm->getRepository(Article::class)->findOneBy(array('title' => 'Статья 1'));
        $this->assertInstanceOf(Article::class, $home);

        $dm->clear();

        $user = $dm->getRepository(User::class)->findOneBy(array('name' => 'Andrey'));

        $articles = $user->getArticles();
        $this->assertCount(1, $articles);
        foreach ($articles as $article) {
            $this->assertInstanceOf(Article::class, $article);
        }
        $articles = current($articles);

    }

    public function testManyToOneReference()
    {
        $dm = $this->getConnection();
        $dm->getConnection()->selectCollection(DOCTRINE_MONGODB_DATABASE, 'users')->getMongoCollection()->getCollection()->deleteMany([]);
        $dm->getConnection()->selectCollection(DOCTRINE_MONGODB_DATABASE, 'posts')->getMongoCollection()->getCollection()->deleteMany([]);
        $dm->getConnection()->selectCollection(DOCTRINE_MONGODB_DATABASE, 'homes')->getMongoCollection()->getCollection()->deleteMany([]);
        $dm->getConnection()->selectCollection(DOCTRINE_MONGODB_DATABASE, 'user_test_data_one')->getMongoCollection()->getCollection()->deleteMany([]);

        $user = new User();
        $dm->persist($user);
        $user->setName('Andrey');
        $user->setEmail('info@rznw.ru');
        $user->setCount('23');

        $testData = new UserTestDataOne();
        $testData->setName('one');
        $testData->setUser($user);
        $dm->persist($testData);

        $dm->flush();

        /** @var User $user */
        $user = $dm->find(User::class, $user->getId());
        /** @var PersistentCollection $dataOne */
        $dataOne = $user->getTestDataOne();
        foreach ($dataOne as $row) {
            echo $row;
        }
        $values = $dataOne->getValues();
        $array = $dataOne->toArray();
        $this->assertCount(1, $array);
    }
}