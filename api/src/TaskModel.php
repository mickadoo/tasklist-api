<?php
namespace MichaelDevery\Tasklist;

use MichaelDevery\Tasklist\Library\AbstractModel;

Class TaskModel extends AbstractModel
{
	/**
	 * @param int $id
	 */
	public function getTask($id)
	{
		$taskData = $this->adapter->read($this->name, $id);
		//todo build Task object and return it;
	}

	//todo add all other methods	
}


