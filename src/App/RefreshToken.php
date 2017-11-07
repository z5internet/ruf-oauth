<?php

namespace z5internet\RufOAuth\App;

use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{

    protected $table = 'oauth_refresh_tokens';

    public $incrementing = false;

    public $timestamps = false;

}
