<?php

declare(strict_types=1);

/*
 * Copyright 2011 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace JMS\TranslationBundle\Translation;

use JMS\TranslationBundle\Exception\InvalidArgumentException;
use JMS\TranslationBundle\Model\MessageCatalogue;
use JMS\TranslationBundle\Translation\Loader\LoaderInterface;
use JMS\TranslationBundle\Util\FileUtils;

class LoaderManager
{
    private $loaders;

    /**
     * @param array $loaders
     */
    public function __construct(array $loaders)
    {
        $this->loaders = $loaders;
    }

    /**
     * @param mixed  $file
     * @param string $format
     * @param string $locale
     * @param string $domain
     *
     * @return mixed
     */
    public function loadFile($file, $format, $locale, $domain = 'messages')
    {
        return $this->getLoader($format)->load($file, $locale, $domain);
    }

    /**
     * @param string $dir
     * @param string $targetLocale
     *
     * @return MessageCatalogue
     */
    public function loadFromDirectory($dir, $targetLocale)
    {
        $files = FileUtils::findTranslationFiles($dir);

        $catalogue = new MessageCatalogue();
        $catalogue->setLocale($targetLocale);

        foreach ($files as $domain => $locales) {
            foreach ($locales as $locale => $data) {
                if ($locale !== $targetLocale) {
                    continue;
                }

                [$format, $file] = $data;

                $catalogue->merge($this->getLoader($format)->load($file, $locale, $domain));
            }
        }

        return $catalogue;
    }

    /**
     * @param string $format
     *
     * @return LoaderInterface
     *
     * @throws \InvalidArgumentException
     */
    protected function getLoader($format)
    {
        if (!isset($this->loaders[$format])) {
            throw new InvalidArgumentException(sprintf('The format "%s" does not exist.', $format));
        }

        return $this->loaders[$format];
    }
}