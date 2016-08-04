<?php
/**
 * EdgeCast API Client Interface
 *
 * @author Digitick <dev@digitick.com>
 */
namespace Digitick\ThirdParty\EdgeCast;

/**
 * EdgeCast API Client Interface
 */
interface ClientInterface
{
    /**
     * Purges the EdgeCast cache for a given pattern
     *
     * @param  int    $media_type
     * @param  string $pattern
     * @return bool
     */
    public function purge($media_type, $pattern);

    /**
     * Preloads the EdgeCast cache for a given url
     *
     * @param  int    $media_type
     * @param  string $url
     * @return bool
     */
    public function load($media_type, $url);
}
