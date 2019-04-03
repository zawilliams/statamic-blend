<?php

namespace Statamic\Addons\Blend;

use Statamic\API\Str;
use Statamic\API\URL;
use Statamic\API\File;
use Statamic\API\Config;
use Statamic\Extend\HasParameters;
use Illuminate\Support\HtmlString;

class Blend
{
    /**
     * Access Statamic's methods for retrieving parameters
     */
    use HasParameters;

    /**
     * Get the path to a versioned Mix file with some Statamic adaptation.
     *
     * @param  string  $path
     * @param  string  $manifestDirectory
     * @return \Illuminate\Support\HtmlString|string
     *
     * @throws \Exception
     */
    public function __invoke($path, $manifestDirectory = '')
    {
        static $manifests = [];

        if (! Str::startsWith($path, '/')) {
            $path = "/{$path}";
        }

        if ($manifestDirectory && ! Str::startsWith($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }

        if (File::exists($this->getPath('hot', $manifestDirectory))) {
            $url = rtrim(File::get($this->getPath('hot', $manifestDirectory)));

            if (Str::startsWith($url, ['http://', 'https://'])) {
                return new HtmlString($this->after($url, ':').$path);
            }

            return new HtmlString("//localhost:8080{$path}");
        }

        $manifestPath = $this->getPath('mix-manifest.json', $manifestDirectory);

        if (! isset($manifests[$manifestPath])) {
            if (! File::exists($manifestPath)) {
                throw new \Exception('The Mix manifest does not exist.');
            }

            $manifests[$manifestPath] = collect(json_decode(File::get($manifestPath), true));
        }

        $manifest = $manifests[$manifestPath];

        $exception = new \Exception("Unable to locate Mix file: {$path}.");

        if (! isset($manifest[$path])) {
            $exception = new \Exception("Unable to locate Mix file: {$path}.");

            if (! app('config')->get('app.debug')) {
                return $path;
            } else {
                throw $exception;
            }
        }

        return $this->themeUrl($manifest[$path]);
    }

    /**
     * Return the remainder of a string after a given value.
     *
     * Since Statamic 2 uses an old version of Laravel, the after() method
     * is missing from the Str class so we have it here.
     *
     * @param  string  $subject
     * @param  string  $search
     * @return string
     */
    private function after($subject, $search)
    {
        return $search === '' ? $subject : array_reverse(explode($search, $subject, 2))[0];
    }

    /**
     * Get the path.
     *
     * @param  string  $path
     * @param  string  $manifestDirectory
     * @return string
     */
    private function getPath($path, $manifestDirectory)
    {
        if ($manifestDirectory) {
            return root_path(
                URL::assemble(
                    $manifestDirectory,
                    $path
                )
            );
        }

        return root_path(
            URL::assemble(
                Config::get('system.filesystems.themes.root'),
                Config::get('theming.theme'),
                $path
            )
        );
    }

    /**
     * Transforms the asset directory into a relative URL for use in the front-end.
     *
     * @param  string  $path
     * @return string
     */
    private function themeUrl($path)
    {
        return URL::assemble(
            Config::get('system.filesystems.themes.url'),
            Config::get('theming.theme'),
            $path
        );
    }
}
