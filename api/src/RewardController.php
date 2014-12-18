<?php
namespace MichaelDevery\Tasklist;

use MichaelDevery\Tasklist\Library\AbstractController;

class RewardController extends AbstractController
{
	/**
	 * @return Response
	 */
	public function getReward($id)
	{
		$RewardData = $this->model->getReward($id);
		die(var_dump($RewardData));
		// todo make proper response
		return new Response();
	}	

	/**
	 * @return Response
	 */
	public function addReward()
	{
		$data = $this->request->getData();
		$newReward = $this->model->addReward($data);
		die(var_dump($newReward));
		// todo make proper response format
		return new Response();

	}

	/**
	 * @param int $id
	 * @return Response
	 */
	public function deleteReward($id)
	{
		$deletedId = $this->model->deleteReward($id);
		die('Deleted : ' . $deletedId);
		// todo make proper response
		return new Response();
	}

	/**
	 * @param int $id
	 * @return Response
	 */
	public function updateReward($id)
	{
		$data = $this->request->getData();
		$updatedReward = $this->model->updateReward($id, $data);
		die('Updated: \n ' . var_dump($updatedReward));
		// todo make proper response
		return new Response();
	}
}
