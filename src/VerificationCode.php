<?php

namespace MEkramy\VerificationCode;

use Illuminate\Http\Request;
use Illuminate\Cache\Repository;

/**
 * Verification code manager
 *
 * @author m ekramy <m@ekramy.ir>
 * @access public
 * @version 1.0.0
 */
class VerificationCode
{
    /**
     * Request instance
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Cache instance
     *
     * @var \Illuminate\Cache\Repository
     */
    protected $cache;

    /**
     * Create a new limiter instance.
     *
     * @return void
     */
    public function __construct(Request $request, Repository $cache)
    {
        $this->request = $request;
        $this->cache = $cache;
    }

    /**
     * Generate code using ip and key combination
     *
     * @param string $key
     * @param bool $global
     * @return string
     */
    protected function key(string $key, bool $global): string
    {
        if ($global === true) {
            return $key;
        }
        return ($this->request->ip() ?? '::1') . '.' . $key;
    }

    /**
     * create new verification code
     *
     * @param string $key
     * @param string $code
     * @param int $ttl
     * @param bool $global
     * @return void
     */
    public function put(string $key, string $code, int $ttl = 5, bool $global = true): void
    {
        $this->cache->put($this->key($key, $global), encrypt($code), $ttl * 60);
    }

    /**
     * Get verification code
     *
     * @param string $key
     * @param bool $global
     * @return string|null
     */
    public function get(string $key, bool $global = true): ?string
    {
        return $this->cache->has($this->key($key, $global)) ? decrypt($this->cache->get($this->key($key))) : null;
    }

    /**
     * Remove verification code
     *
     * @param string $key
     * @param bool $global
     * @return void
     */
    public function remove(string $key, bool $global = true): void
    {
        $this->cache->forget($this->key($key, $global));
    }

    /**
     * check if verification code exists
     *
     * @param string $key
     * @param bool $global
     * @return bool
     */
    public function exists(string $key, bool $global = true): bool
    {
        return $this->cache->has($this->key($key, $global));
    }
}
