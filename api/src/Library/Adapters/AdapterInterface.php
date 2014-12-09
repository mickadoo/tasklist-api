<?php
namespace MichaelDevery\Tasklist\Library\Adapters;

Interface AdapterInterface
{
	public function create();

	public function read();

	public function update();

	public function delete();
}
