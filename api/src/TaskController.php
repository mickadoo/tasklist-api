<?php
namespace MichaelDevery\Tasklist;

use MichaelDevery\Tasklist\Library\AbstractController; 

class TaskController extends AbstractController
{
	public function getTask($id)
	{
		return  "Returning task object with id : $id";
	}	
}
