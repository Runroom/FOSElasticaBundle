<?php

declare(strict_types=1);

/*
 * This file is part of the FOSElasticaBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\ElasticaBundle\Event;

use Symfony\Component\EventDispatcher\Event as LegacyEvent;
use Symfony\Contracts\EventDispatcher\Event;

if (!class_exists(Event::class)) {
    /**
     * Symfony 3.4.
     */
    abstract class AbstractEvent extends LegacyEvent
    {
    }
} else {
    /**
     * Symfony >= 4.3.
     */
    abstract class AbstractEvent extends Event
    {
    }
}
