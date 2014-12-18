<?php
namespace MichaelDevery\Tasklist\Models;

trait SerializableTrait
{
    /**
     * @return array
     */
    public function toArray()
    {
        return array_filter(get_object_vars($this));
    }
}
