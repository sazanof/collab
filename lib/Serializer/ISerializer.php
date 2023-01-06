<?php

namespace CLB\Serializer;

interface ISerializer
{
    public function serialize(mixed $data);

    public static function serializeStatic(mixed $data);
}