<?php
namespace MichaelDevery\Tasklist\Models;

trait SerializableTrait
{
    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }
}
