<?php

/*
 * This file is part of the FOSElasticaBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\ElasticaBundle\Serializer;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface as JMSSerializer;
use Symfony\Component\Serializer\SerializerInterface;

class Callback
{
    protected $serializer;
    protected $groups = [];
    protected $version;
    protected $serializeNull;

    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
        if (!method_exists($this->serializer, 'serialize')) {
            throw new \RuntimeException('The serializer must have a "serialize" method.');
        }
    }

    public function setGroups(array $groups)
    {
        $this->groups = $groups;

        if (!empty($this->groups) && !$this->serializer instanceof SerializerInterface && !$this->serializer instanceof JMSSerializer) {
            throw new \RuntimeException('Setting serialization groups requires using "JMS\Serializer\Serializer" or "Symfony\Component\Serializer\Serializer"');
        }
    }

    public function setVersion($version)
    {
        $this->version = $version;

        if ($this->version && !$this->serializer instanceof JMSSerializer) {
            throw new \RuntimeException('Setting serialization version requires using "JMS\Serializer\Serializer".');
        }
    }

    public function setSerializeNull($serializeNull)
    {
        $this->serializeNull = $serializeNull;

        if (true === $this->serializeNull && !$this->serializer instanceof JMSSerializer) {
            throw new \RuntimeException('Setting null value serialization option requires using "JMS\Serializer\Serializer".');
        }
    }

    public function serialize($object)
    {
        $context = $this->serializer instanceof JMSSerializer ? SerializationContext::create()->enableMaxDepthChecks() : [];

        if (!empty($this->groups)) {
            if ($context instanceof SerializationContext) {
                $context->setGroups($this->groups);
            } else {
                $context['groups'] = $this->groups;
            }
        }

        if ($this->version) {
            $context->setVersion($this->version);
        }

        if (!is_array($context)) {
            $context->setSerializeNull($this->serializeNull);
        }

        return $this->serializer->serialize($object, 'json', $context);
    }
}
