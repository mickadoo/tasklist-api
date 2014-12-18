<?php
namespace MichaelDevery\Tasklist\Models;

trait HydratableTrait
{
	public function hydrate($data){
        foreach ($data as $field => $value){
            $setter = 'set' . ucfirst($field);
            if (method_exists($this, $setter)){
                $this->$setter($value);
            }
        }
    }
}
