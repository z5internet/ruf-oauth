<?php

namespace z5internet\RufOAuth\App\Bridge;

use Laravel\Passport\Events\RefreshTokenCreated;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

use z5internet\RufOAuth\App\RefreshToken as RefreshTokenModel;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /**
     * The access token repository instance.
     *
     * @var \Laravel\Passport\Bridge\AccessTokenRepository
     */
    protected $tokens;

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * Create a new repository instance.
     *
     * @param  \Laravel\Passport\Bridge\AccessTokenRepository  $tokens
     * @param  \Illuminate\Database\Connection  $database
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function __construct(AccessTokenRepository $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewRefreshToken()
    {
        return new RefreshToken;
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        RefreshTokenModel::insert([
            'id' => $id = $refreshTokenEntity->getIdentifier(),
            'access_token_id' => $accessTokenId = $refreshTokenEntity->getAccessToken()->getIdentifier(),
            'revoked' => false,
            'expires_at' => $refreshTokenEntity->getExpiryDateTime(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function revokeRefreshToken($tokenId)
    {
        RefreshTokenModel::where('id', $tokenId)->update(['revoked' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        $refreshToken = RefreshTokenModel::where('id', $tokenId)->first();

        if ($refreshToken === null || $refreshToken->revoked) {
            return true;
        }

        return $this->tokens->isAccessTokenRevoked(
            $refreshToken->access_token_id
        );
    }
}
