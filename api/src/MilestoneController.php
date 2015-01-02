<?php
namespace MichaelDevery\Tasklist;

use MichaelDevery\Tasklist\Library\AbstractController;
use MichaelDevery\Tasklist\Models\Milestone;

class MilestoneController extends AbstractController
{
	/**
	 * @return Response
	 */
	public function getMilestone($id)
	{
		/** @var Milestone $milestone */
		$milestone = $this->model->getMilestone($id);
		$response = new Response();
		$response->setCode(200);
		$response->setData(json_encode($milestone));
		$response->setResourceUrl($this->getBaseUrl() . '/' . lcfirst($this->model->getName()) . '/' . $milestone->getId());
		return $response;
	}	

	/**
	 * @return Response
	 */
	public function addMilestone()
	{
		$data = $this->request->getData();
		/** @var Milestone $milestone */
		$milestone = $this->model->addMilestone($data);

		$response = new Response();
		$response->setCode(201);
		$response->setData(json_encode($milestone));
		$response->setResourceUrl($this->getBaseUrl() . '/' . lcfirst($this->model->getName()) . '/' . $milestone->getId());
		return $response;
	}

	/**
	 * @param int $id
	 * @return Response
	 */
	public function deleteMilestone($id)
	{
		$deletedId = $this->model->deleteMilestone($id);
		$response = new Response();
		$response->setCode(200);
		$response->setData(['id' => $deletedId]);
		return $response;
	}

	/**
	 * @param int $id
	 * @return Response
	 */
	public function updateMilestone($id)
	{
		$data = $this->request->getData();
		/** @var Milestone $updatedMilestone */
		$updatedMilestone = $this->model->updateMilestone($id, $data);

		$response = new Response();
		$response->setCode(200);
		$response->setData(json_encode($updatedMilestone));
		$response->setResourceUrl($this->getBaseUrl() . '/' . lcfirst($this->model->getName()) . '/' . $updatedMilestone->getId());
		return $response;
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
