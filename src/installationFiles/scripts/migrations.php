<?php

namespace z5internet\RufOAuth\installationFiles\scripts;

use Illuminate\Filesystem\Filesystem;

class migrations
{

    public function install()
    {

        $migrationFiles = [
            'create_oauth_personal_access_clients_table',
            'create_oauth_auth_codes_table',
            'create_oauth_access_tokens_table',
            'create_oauth_refresh_tokens_table',
            'create_oauth_clients_table',
        ];

        foreach ($migrationFiles as $key => $value) {

            $time = '2017_06_01_0000'.str_pad($key, 2, 0, STR_PAD_LEFT);

            copy(
                __DIR__.'/../database/migrations/'.$value.'.php',
                database_path('migrations/'.$time.'_'.$value.'.php')
            );
        }
    }

}