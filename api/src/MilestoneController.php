<?php
namespace MichaelDevery\Tasklist;

use MichaelDevery\Tasklist\Library\AbstractController;
use MichaelDevery\Tasklist\Models\Milestone;

class MilestoneController extends AbstractController
{
	/**
	 * @param $id
	 * @param null $parentId
	 * @return Response
	 */
	public function getMilestone($id, $parentId = null)
	{
		/** @var Milestone $milestone */
		$milestone = $this->model->getMilestone($id, $parentId);
		$response = new Response();
		$response->setCode(200);
		$response->setData(json_encode($milestone));
		$response->setResourceUrl($this->getBaseUrl() . '/' . lcfirst($this->model->getName()) . '/' . $milestone->getId());
		return $response;
	}

	/**
	 * @param int $id
	 * @param int $parentId
	 * @return Response
	 */
	public function addMilestone($id = null, $parentId = null)
	{
		$data = $this->request->getData();
		$milestone = new Milestone($data);

		if ($parentId){
			$milestone->setParentId($parentId);
		}

		/** @var Milestone $milestone */
		$newMilestone = $this->model->addMilestone($milestone);

		$response = new Response();
		$response->setCode(201);
		$response->setData(json_encode($newMilestone));
		$response->setResourceUrl($this->getBaseUrl() . '/' . lcfirst($this->model->getName()) . '/' . $newMilestone->getId());
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

	public function deleteAllMilestones($id = null, $parentId = null)
	{
		$deletedIds = $this->model->deleteAllMilestones($parentId);

		$response = new Response();
		$response->setCode(200);

		$response->setData(json_encode($deletedIds));

		return $response;
	}

	public function getAllMilestones($id = null, $parentId = null)
	{
		$milestones = $this->model->getAllMilestones($parentId);

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
