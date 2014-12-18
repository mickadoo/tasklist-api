<?php
namespace MichaelDevery\Tasklist\Library\Adapters;

Interface AdapterInterface
{
	public function create($resourceName, $data);

	public function read($resourceName, $id = null, $fields = null);

	public function update($resourceName, $id, $data);

	public function delete($resourceName, $id = null);
}
