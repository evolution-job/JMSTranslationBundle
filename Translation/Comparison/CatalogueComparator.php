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

namespace JMS\TranslationBundle\Translation\Comparison;

use JMS\TranslationBundle\Model\MessageCatalogue;

/**
 * Compares two message catalogues.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class CatalogueComparator
{
    private $domains = [];
    private $ignoredDomains = [];

    public function setDomains(array $domains): void
    {
        $this->domains = $domains;
    }

    /**
     * @param array $domains
     */
    public function setIgnoredDomains(array $domains): void
    {
        $this->ignoredDomains = $domains;
    }

    /**
     * Compares two message catalogues.
     *
     * @param MessageCatalogue $current
     * @param MessageCatalogue $new
     *
     * @return ChangeSet
     */
    public function compare(MessageCatalogue $current, MessageCatalogue $new): ChangeSet
    {

        $newMessages = $this->extractMessages($new, $current);
        $deletedMessages = $this->extractMessages($current, $new);

        return new ChangeSet($newMessages, $deletedMessages);
    }

    /**
     * @param MessageCatalogue $catalogue
     * @param MessageCatalogue $otherCtalogue
     * @return array
     */
    private function extractMessages(MessageCatalogue $catalogue, MessageCatalogue $otherCtalogue): array
    {
        $messages = [];
        foreach ($catalogue->getDomains() as $name => $domain) {
            if ($this->domains && !isset($this->domains[$name])) {
                continue;
            }

            if (isset($this->ignoredDomains[$name])) {
                continue;
            }

            foreach ($domain->all() as $message) {
                if ($otherCtalogue->has($message)) {
                    // FIXME: Compare what has changed

                    continue;
                }

                $messages[] = $message;
            }
        }

        return $messages;
    }
}
