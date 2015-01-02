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
		die('milestone: ' . var_dump($MilestoneData));
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
		die('milestone: ' . var_dump($newMilestone));
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

	public function deleteAllMilestones()
	{
		$deletedIds = $this->model->deleteAllMilestones();

		$response = new Response();
		$response->setCode(200);

		$response->setData(json_encode($deletedIds));

		return $response;
	}

	public function getAllMilestones()
	{
		$milestones = $this->model->getAllMilestones();

		// have to call on each one
		foreach ($milestones as $key => $milestone){
			$milestones[$key] = json_encode($milestone);
		}

		$response = new Response();
		$response->setCode(200);

		$response->setData(json_encode($milestones));
		$response->setResourceUrl($this->getBaseUrl() . '/' . lcfirst($this->model->getName()) . '/');

		return $response;
	}

}
