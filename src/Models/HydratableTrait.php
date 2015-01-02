<?php
namespace MichaelDevery\Tasklist\Models;

use MichaelDevery\Tasklist\FrontController;
use MichaelDevery\Tasklist\Library\ApiException;

trait HydratableTrait
{
    /**
     * @param array $data
     * @param array $subClasses
     * @param array $setMethodMapper
     */
	public function hydrate(array $data, array $subClasses = array(), array $setMethodMapper = array())
    {
        foreach ($data as $field => $value){
            if (!in_array($field, $subClasses)){
                $this->setValue($field, $value, $setMethodMapper);
            } else {
                // set sub class
                $subClassesData = $data[$field];
                $subClassCollection = array();
                foreach ($subClassesData as $classData){
                    // prepare class name with namespace
                    $className = __NAMESPACE__ . '\\' . ucfirst(FrontController::singularize($field));
                    $subClass = new $className($classData);
                    $subClassCollection[] = $subClass;
                }
                $this->setValue($field, $subClassCollection, $setMethodMapper);
            }
        }
    }

    /**
     * @param string $field
     * @param mixed $value
     * @param array $setMethodMapper
     * @throws ApiException
     */
    private function setValue($field, $value, $setMethodMapper)
    {
        $setter = 'set' . ucfirst($field);
        if (method_exists($this, $setter)){
            $this->$setter($value);
        } else {
            if ((isset($setMethodMapper[$setter]) && method_exists($this, $setMethodMapper[$setter]))){
                $this->$setMethodMapper[$setter]($value);
            } else {
                throw new ApiException(400, "Trying to set property '$field' which does not exist");
            }
        }
    }
}
