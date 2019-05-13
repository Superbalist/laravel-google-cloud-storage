<?php

namespace Superbalist\LaravelGoogleCloudStorage;

use Illuminate\Support\Arr;
use Illuminate\Filesystem\Cache;
use League\Flysystem\Filesystem;
use League\Flysystem\AdapterInterface;
use Illuminate\Support\ServiceProvider;
use Google\Cloud\Storage\StorageClient;
use League\Flysystem\Cached\CachedAdapter;
use Illuminate\Filesystem\FilesystemManager;
use League\Flysystem\Cached\Storage\Memory as MemoryStore;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

class GoogleCloudStorageServiceProvider extends ServiceProvider
{
    /**
     * Create a Filesystem instance with the given adapter.
     *
     * @param  \League\Flysystem\AdapterInterface                   $adapter
     * @param  array                                                $config
     * @return \League\Flysystem\FlysystemInterfaceAdapterInterface
     */
    protected function createFilesystem(AdapterInterface $adapter, array $config)
    {
        $cache = Arr::pull($config, 'cache');

        $config = Arr::only($config, ['visibility', 'disable_asserts', 'url']);

        if ($cache) {
            $adapter = new CachedAdapter($adapter, $this->createCacheStore($cache));
        }

        return new Filesystem($adapter, count($config) > 0 ? $config : null);
    }

    /**
     * Create a cache store instance.
     *
     * @param  mixed                                   $config
     * @return \League\Flysystem\Cached\CacheInterface
     *
     * @throws \InvalidArgumentException
     */
    protected function createCacheStore($config)
    {
        if ($config === true) {
            return new MemoryStore();
        }

        return new Cache(
            $this->app['cache']->store($config['store']),
            array_get($config, 'prefix', 'flysystem'),
            array_get($config, 'expire')
        );
    }

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $factory = $this->app->make('filesystem');
        // @var FilesystemManager $factory
        $factory->extend('gcs', function ($app, $config) {
            $storageClient = $this->createClient($config);

            $bucket = $storageClient->bucket($config['bucket']);
            $pathPrefix = array_get($config, 'path_prefix');
            $storageApiUri = array_get($config, 'storage_api_uri');

            $adapter = new GoogleStorageAdapter($storageClient, $bucket, $pathPrefix, $storageApiUri);

            return $this->createFilesystem($adapter, $config);
        });
    }

    /**
     * Create a new StorageClient
     *
     * @param  mixed                               $config
     * @return \Google\Cloud\Storage\StorageClient
     */
    private function createClient($config)
    {
        //Get the keyfile config from the config/filesystems.php
        $keyFilePath = array_get($config, 'key_file_path');

        if (is_string($keyFilePath) && !empty($keyFilePath)) {
            return new StorageClient([
                'projectId' => $config['project_id'],
                'keyFilePath' => $keyFilePath,
            ]);
        }

        //Get the keyFile array from the disk in config/filesystems.php
        $keyFile = array_get($config, 'key_file');

        if (is_array($keyFile) && !empty($keyFile)) {
            return new StorageClient([
                'projectId' => $config['project_id'],
            ]);
        }

        //If we don't have a keyFilePath or keyFile let
        //the Google\Cloud\Storage\StorageClient try
        //to authticate for us via Google App or
        //Google Compute Engine instances or
        //GOOGLE_APPLICATION_CREDENTIALS
        return new StorageClient([
            'projectId' => $config['project_id'],
        ]);
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
    }
}
