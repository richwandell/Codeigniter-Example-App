<?php
/**
 * Using Doctrine with Codeigniter is a bit strange
 * First we need to make a Doctrine Library that we can autoload in config.php
 *
 * Also I wasn't able to get the composer autoloader to load my entities
 * so for the purposes of this quick and dirty example App I am just using
 * a require_once for my entity files.
 */
use Doctrine\Common\ClassLoader;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\DBAL\Logging\EchoSQLLogger;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

class Doctrine
{

    public $em = null;

    public function __construct()
    {
        // load database configuration from CodeIgniter
        require_once APPPATH.'config/database.php';

        $doctrineClassLoader = new ClassLoader('Doctrine', APPPATH.'libraries');
        $doctrineClassLoader->register();
        $entitiesClassLoader = new ClassLoader('models', rtrim(APPPATH, "/"));
        $entitiesClassLoader->register();
        $proxiesClassLoader = new ClassLoader('Proxies', APPPATH.'models/proxies');
        $proxiesClassLoader->register();

        // Set up caches
        $config = new Configuration();
        $cache = new ArrayCache();
        $config->setMetadataCacheImpl($cache);

        $reader = new AnnotationReader();
        $driverImpl = new AnnotationDriver($reader, array(APPPATH."models/Entities"));
        $config->setMetadataDriverImpl($driverImpl);
        $config->setQueryCacheImpl($cache);

        // Proxy configuration
        $config->setProxyDir(APPPATH.'/models/proxies');
        $config->setProxyNamespace('Proxies');
        $config->setAutoGenerateProxyClasses(true);
        AnnotationRegistry::registerLoader('class_exists');

        // Database connection information
        $connectionOptions = array(
            'driver' => 'pdo_mysql',
            'user' => $db['default']['username'],
            'password' => $db['default']['password'],
            'host' => $db['default']['hostname'],
            'dbname' => $db['default']['database'],
        );

        // Create EntityManager
        $this->em = EntityManager::create($connectionOptions, $config);
    }
}

foreach (scandir(APPPATH."models/Entities") as $file) {
    if (substr($file, -4) !== ".php") {
        continue;
    }
    require_once APPPATH."models/Entities/$file";
}
