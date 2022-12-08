<?php


namespace PriorityApi\Exceptions;


use RuntimeException;

class JsonEncodingException extends RuntimeException
{
    /**
     * Create a new JSON encoding exception for the entity.
     *
     * @param mixed  $entity
     * @param string $message
     *
     * @return static
     */
    public static function forEntity($entity, $message)
    {
        return new static('Error encoding entity [' . get_class($entity) . '] with ID [' . $entity->getKey() . '] to JSON: ' . $message);
    }

    /**
     * Create a new JSON encoding exception for an attribute.
     *
     * @param mixed  $entity
     * @param mixed  $key
     * @param string $message
     *
     * @return static
     */
    public static function forAttribute($entity, $key, $message)
    {
        $class = get_class($entity);

        return new static("Unable to encode attribute [{$key}] for entity [{$class}] to JSON: {$message}.");
    }
}
