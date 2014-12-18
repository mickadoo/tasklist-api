<?php
namespace MichaelDevery\Tasklist;

use MichaelDevery\Tasklist\Library\AbstractController; 

class MilestoneController extends AbstractController
{
	/**
	 * @return Response
	 */
	public function getMilestone($id)
	{
		$MilestoneData = $this->model->getMilestone($id);
		die(var_dump($MilestoneData));
		// todo make proper response
		return new Response();
	}	

	/**
	 * @return Response
	 */
	public function addMilestone()
	{
		$data = $this->request->getData();
		$newMilestone = $this->model->addMilestone($data);
		die(var_dump($newMilestone));
		// todo make proper response format
		return new Response();

	}

	/**
	 * @param int $id
	 * @return Response
	 */
	public function deleteMilestone($id)
	{
		$deletedId = $this->model->deleteMilestone($id);
		die('Deleted : ' . $deletedId);
		// todo make proper response
		return new Response();
	}

	/**
	 * @param int $id
	 * @return Response
	 */
	public function updateMilestone($id)
	{
		$data = $this->request->getData();
		$updatedMilestone = $this->model->updateMilestone($id, $data);
		die('Updated: \n ' . var_dump($updatedMilestone));
		// todo make proper response
		return new Response();
	}
}
