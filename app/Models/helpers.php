<?php


use Dotenv\Repository\Adapter\ImmutableWriter;
use Dotenv\Repository\AdapterRepository;
use Illuminate\Support\Env;

if ( ! function_exists('setEnv'))
{
    function setEnv($key, $value)
    {
        $path = app()->environmentFilePath();

        $escaped = preg_quote('='.env($key), '/');

        file_put_contents($path, preg_replace(
            "/^{$key}{$escaped}/m",
            "{$key}={$value}",
            file_get_contents($path)
        ));
    }
}
