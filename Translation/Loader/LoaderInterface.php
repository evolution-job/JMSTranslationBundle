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

namespace JMS\TranslationBundle\Translation\Loader;

use JMS\TranslationBundle\Model\MessageCatalogue;

/**
 * Loader Interface for the bundle.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface LoaderInterface
{
    /**
     * Loads a MessageCatalogue from the file.
     *
     * The difference to Symfony's interface is that any loader is
     * expected to return the MessageCatalogue from the bundle which
     * contains more translation information.
     *
     * @param mixed  $resource
     * @param string $locale
     * @param string $domain
     *
     * @return MessageCatalogue
     */
    public function load(mixed $resource, string $locale, string $domain = 'messages'): MessageCatalogue;
}
