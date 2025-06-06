<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for database operations. This is
    | the connection which will be utilized unless another connection
    | is explicitly specified when you execute a query / statement.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Below are all of the database connections defined for your application.
    | An example configuration is provided for each database system which
    | is supported by Laravel. You're free to add / remove connections.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
            'busy_timeout' => null,
            'journal_mode' => null,
            'synchronous' => null,
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => [
                PDO::ATTR_EMULATE_PREPARES => true,
                PDO::ATTR_PERSISTENT => false,
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            ],
        ],

        'mariadb' => [
            'driver' => 'mariadb',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            // 'encrypt' => env('DB_ENCRYPT', 'yes'),
            // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
        ],

    'mysql_bau_bau' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_UNIT_BAU_BAU_DATABASE', 'forge'),
    'username' => env('DB_USERNAME_BAU_BAU', 'forge'),
    'password' => env('DB_PASSWORD_BAU_BAU', ''),
    'unix_socket' => env('DB_SOCKET', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
],

'mysql_kolaka' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_UNIT_KOLAKA_DATABASE', 'forge'),
    'username' => env('DB_USERNAME_KOLAKA', 'forge'),
    'password' => env('DB_PASSWORD_KOLAKA', ''),
    'unix_socket' => env('DB_SOCKET', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'modes' => [
        'NO_ENGINE_SUBSTITUTION'
    ],
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_STRINGIFY_FETCHES => true,
        PDO::MYSQL_ATTR_DIRECT_QUERY => true,   
        PDO::ATTR_PERSISTENT => false
    ]
],

'mysql_poasia' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_UNIT_POASIA_DATABASE', 'forge'),
    'username' => env('DB_USERNAME_POASIA', 'forge'),
    'password' => env('DB_PASSWORD_POASIA', ''),
    'unix_socket' => env('DB_SOCKET', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => false,
    'engine' => null,
    'modes' => [
        'NO_ENGINE_SUBSTITUTION'
    ],
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_STRINGIFY_FETCHES => true,
        PDO::MYSQL_ATTR_DIRECT_QUERY => true,
        PDO::ATTR_PERSISTENT => false
    ],
],

'mysql_wua_wua' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_UNIT_WUA_WUA_DATABASE', 'forge'),
    'username' => env('DB_USERNAME_WUA_WUA', 'forge'),
    'password' => env('DB_PASSWORD_WUA_WUA', ''),
    'unix_socket' => env('DB_SOCKET', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'modes' => [
        'NO_ENGINE_SUBSTITUTION'
    ],
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_STRINGIFY_FETCHES => true,
        PDO::MYSQL_ATTR_DIRECT_QUERY => true,
        PDO::ATTR_PERSISTENT => false
    ]
],

'u478221055_up_kendari' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => 'u478221055_up_kendari',
    'username' => env('DB_USERNAME'),
    'password' => env('DB_PASSWORD'),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => false,
    'engine' => null,
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::ATTR_PERSISTENT => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'"
    ],
],



'mysql_ereke' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_UNIT_PLTD_EREKE_DATABASE', 'forge'),
    'username' => env('DB_USERNAME_PLTD_EREKE', 'forge'),
    'password' => env('DB_PASSWORD_PLTD_EREKE', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'modes' => [
        'NO_ENGINE_SUBSTITUTION'
    ],
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_STRINGIFY_FETCHES => true,
        PDO::MYSQL_ATTR_DIRECT_QUERY => true,
        PDO::ATTR_PERSISTENT => false
    ]
],



'mysql_ladumpi' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_UNIT_PLTD_LADUMPI_DATABASE', 'forge'),
    'username' => env('DB_USERNAME_PLTD_LADUMPI', 'forge'),
    'password' => env('DB_PASSWORD_PLTD_LADUMPI', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'modes' => [
        'NO_ENGINE_SUBSTITUTION'
    ],
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_STRINGIFY_FETCHES => true,
        PDO::MYSQL_ATTR_DIRECT_QUERY => true,
        PDO::ATTR_PERSISTENT => false
    ]
],

'mysql_langara' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_UNIT_PLTD_LANGARA_DATABASE', 'forge'),
    'username' => env('DB_USERNAME_PLTD_LANGARA', 'forge'),
    'password' => env('DB_PASSWORD_PLTD_LANGARA', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'modes' => [
        'NO_ENGINE_SUBSTITUTION'
    ],
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_STRINGIFY_FETCHES => true,
        PDO::MYSQL_ATTR_DIRECT_QUERY => true,
        PDO::ATTR_PERSISTENT => false
    ]
],

'mysql_lanipa_nipa' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_UNIT_PLTD_LANIPA_NIPA_DATABASE', 'forge'),
    'username' => env('DB_USERNAME_PLTD_LANIPA_NIPA', 'forge'),
    'password' => env('DB_PASSWORD_PLTD_LANIPA_NIPA', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'modes' => [
        'NO_ENGINE_SUBSTITUTION'
    ],
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_STRINGIFY_FETCHES => true,
        PDO::MYSQL_ATTR_DIRECT_QUERY => true,
        PDO::ATTR_PERSISTENT => false
    ]
],

'mysql_pasarwajo' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_UNIT_PLTD_PASARWAJO_DATABASE', 'forge'),
    'username' => env('DB_USERNAME_PLTD_PASARWAJO', 'forge'),
    'password' => env('DB_PASSWORD_PLTD_PASARWAJO', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'modes' => [
        'NO_ENGINE_SUBSTITUTION'
    ],
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_STRINGIFY_FETCHES => true,
        PDO::MYSQL_ATTR_DIRECT_QUERY => true,
        PDO::ATTR_PERSISTENT => false
    ]
],



'mysql_poasia_containerized' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_UNIT_PLTD_POASIA_CONTAINERIZED_DATABASE', 'forge'),
    'username' => env('DB_USERNAME_PLTD_POASIA_CONTAINERIZED', 'forge'),
    'password' => env('DB_PASSWORD_PLTD_POASIA_CONTAINERIZED', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'modes' => [
        'NO_ENGINE_SUBSTITUTION'
    ],
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_STRINGIFY_FETCHES => true,
        PDO::MYSQL_ATTR_DIRECT_QUERY => true,
        PDO::ATTR_PERSISTENT => false
    ]
],

'mysql_raha' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_UNIT_PLTD_RAHA_DATABASE', 'forge'),
    'username' => env('DB_USERNAME_PLTD_RAHA', 'forge'),
    'password' => env('DB_PASSWORD_PLTD_RAHA', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'modes' => [
        'NO_ENGINE_SUBSTITUTION'
    ],
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_STRINGIFY_FETCHES => true,
        PDO::MYSQL_ATTR_DIRECT_QUERY => true,
        PDO::ATTR_PERSISTENT => false
    ]
],

'mysql_wangi_wangi' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_UNIT_PLTD_WANGI_WANGI_DATABASE', 'forge'),
    'username' => env('DB_USERNAME_PLTD_WANGI_WANGI', 'forge'),
    'password' => env('DB_PASSWORD_PLTD_WANGI_WANGI', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'modes' => [
        'NO_ENGINE_SUBSTITUTION'
    ],
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_STRINGIFY_FETCHES => true,
        PDO::MYSQL_ATTR_DIRECT_QUERY => true,
        PDO::ATTR_PERSISTENT => false
    ]
],



// PLTM Units
'mysql_mikuasi' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_UNIT_PLTM_MIKUASI_DATABASE', 'forge'),
    'username' => env('DB_USERNAME_PLTM_MIKUASI', 'forge'),
    'password' => env('DB_PASSWORD_PLTM_MIKUASI', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'modes' => [
        'NO_ENGINE_SUBSTITUTION'
    ],
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_STRINGIFY_FETCHES => true,
        PDO::MYSQL_ATTR_DIRECT_QUERY => true,
        PDO::ATTR_PERSISTENT => false
    ]
],

'mysql_rongi' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_UNIT_PLTM_RONGI_DATABASE', 'forge'),
    'username' => env('DB_USERNAME_PLTM_RONGI', 'forge'),
    'password' => env('DB_PASSWORD_PLTM_RONGI', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'modes' => [
        'NO_ENGINE_SUBSTITUTION'
    ],
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_STRINGIFY_FETCHES => true,
        PDO::MYSQL_ATTR_DIRECT_QUERY => true,
        PDO::ATTR_PERSISTENT => false
    ]
],

'mysql_sabilambo' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_UNIT_PLTM_SABILAMBO_DATABASE', 'forge'),
    'username' => env('DB_USERNAME_PLTM_SABILAMBO', 'forge'),
    'password' => env('DB_PASSWORD_PLTM_SABILAMBO', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'modes' => [
        'NO_ENGINE_SUBSTITUTION'
    ],
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_STRINGIFY_FETCHES => true,
        PDO::MYSQL_ATTR_DIRECT_QUERY => true,
        PDO::ATTR_PERSISTENT => false
    ]
],

'mysql_winning' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_UNIT_PLTM_WINNING_DATABASE', 'forge'),
    'username' => env('DB_USERNAME_PLTM_WINNING', 'forge'),
    'password' => env('DB_PASSWORD_PLTM_WINNING', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'modes' => [
        'NO_ENGINE_SUBSTITUTION'
    ],
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_STRINGIFY_FETCHES => true,
        PDO::MYSQL_ATTR_DIRECT_QUERY => true,
        PDO::ATTR_PERSISTENT => false
    ]
],

// PLTMG Units
'mysql_pltmg_bau_bau' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_UNIT_PLTMG_BAU_BAU_DATABASE', 'forge'),
    'username' => env('DB_USERNAME_PLTMG_BAU_BAU', 'forge'),
    'password' => env('DB_PASSWORD_PLTMG_BAU_BAU', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'modes' => [
        'NO_ENGINE_SUBSTITUTION'
    ],
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_STRINGIFY_FETCHES => true,
        PDO::MYSQL_ATTR_DIRECT_QUERY => true,
        PDO::ATTR_PERSISTENT => false
    ]
],

'mysql_kendari' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_UNIT_PLTMG_KENDARI_DATABASE', 'forge'),
    'username' => env('DB_USERNAME_PLTMG_KENDARI', 'forge'),
    'password' => env('DB_PASSWORD_PLTMG_KENDARI', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'modes' => [
        'NO_ENGINE_SUBSTITUTION'
    ],
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_STRINGIFY_FETCHES => true,
        PDO::MYSQL_ATTR_DIRECT_QUERY => true,
        PDO::ATTR_PERSISTENT => false
    ]
],

// PLTU Units
'mysql_baruta' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_UNIT_PLTU_BARUTA_DATABASE', 'forge'),
    'username' => env('DB_USERNAME_PLTU_BARUTA', 'forge'),
    'password' => env('DB_PASSWORD_PLTU_BARUTA', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'modes' => [
        'NO_ENGINE_SUBSTITUTION'
    ],
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_STRINGIFY_FETCHES => true,
        PDO::MYSQL_ATTR_DIRECT_QUERY => true,
        PDO::ATTR_PERSISTENT => false
    ]
],

'mysql_moramo' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_UNIT_PLTU_MORAMO_DATABASE', 'forge'),
    'username' => env('DB_USERNAME_PLTU_MORAMO', 'forge'),
    'password' => env('DB_PASSWORD_PLTU_MORAMO', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'modes' => [
        'NO_ENGINE_SUBSTITUTION'
    ],
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_STRINGIFY_FETCHES => true,
        PDO::MYSQL_ATTR_DIRECT_QUERY => true,
        PDO::ATTR_PERSISTENT => false
    ]
],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run on the database.
    |
    */

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as Memcached. You may define your connection settings here.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];