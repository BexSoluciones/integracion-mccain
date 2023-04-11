<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
        ],

        'ftp' => [
            'driver' => env('FTP_DRIVER','ftp'),
            'host' => env('FTP_HOST','mars.cdtbigdata.com'),
            'username' => env('FTP_USERNAME','mars.pandapan'),
            'password' => env('FTP_PASSWORD','py9BdP'),
            'port' => env('FTP_PORT',22),
            'ignorePassiveAddress' => true,
            // 'root' => env('FTP_ROOT',''),
            // 'passive' => env('FTP_PASSIVE',true),
            // 'ssl' => env('FTP_SSL',true),
            // 'timeout' => env('FTP_TI',30),
        ],

        'sftp' => [
            'driver' => env('SFTP_DRIVER','sftp'),
            'host' => env('SFTP_HOST','mars.cdtbigdata.com'),
            'username' => env('SFTP_USERNAME','mars.pandapan'),
            'password' => env('SFTP_PASSWORD','py9BdP'),
            //'privateKey' => env('SFTP_PRIVATE_KEY',''),
            //'password' => env('SFTP_PASSWORD_KEY',''),
             'port' => env('FTP_PORT',22),
             'ignorePassiveAddress' => true,
            // 'root' => env('FTP_ROOT'),
            // 'timeout' => env('FTP_TI'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
