<?php
namespace MichaelDevery\Tasklist\Library\Adapters;

class CsvAdapter implements AdapterInterface
{

	const FILE_FORMAT = '.csv';

	/** @var string */
	protected $dataFolder = 'csv_data/';

	public function create($name, $data)
	{
		$this->createResourceFileIfNotExists($name);
		$fileName = $this->getResourceFileName($name);
		
		// get last id
		$id = $this->getLastId($name);
		$newId = $id + 1;
		// open file for appending
		$handle = fopen($fileName,'a');
		// merge new id with data while un-setting old array keys (for mapping)
		$data = array_merge([$newId], array_values($data));
		// write data to end of file
		fputcsv($handle,$data);
		// save and return new id
		fclose($handle);
		return $data;
	}

	public function read($name, $id = null, $fields = null)
	{
		if (!$this->resourceFileExists($name)){
			// todo handle this better
			die('no resource');
		}
		$file = fopen($this->getResourceFileName($name), 'r');

		$results = array();

		while(! feof($file)){
			$row = fgetcsv($file);
			if ($id && $row){
				if ($id === (int) $row[0]){
					return $row;
				}
			} elseif ($row) { // prevents 'false' being returns for last row
				$results[] = $row;
			}
		}
        return $results;
	}

	public function update($name, $id, $data)
	{
		// open file
		$file = fopen($this->getResourceFileName($name), 'r');
		// create temp file
		$tempFileName = __DIR__ . '/' . $this->getDataFolder() . 'temp.csv';
		$tempFile = fopen($tempFileName, 'w');
		// loop through file and write if doesn't match id
		$found = false;
		if ($file){
			while (! feof($file)){
				$row = fgetcsv($file);
				if ($row && $id !== (int) $row[0]){
					fputcsv($tempFile, $row);
				} elseif ($row) {
					fputcsv($tempFile, array_merge([$id], $data));
					$found = true;
				}
			}
		} else {
			// todo handle better
			die('no file');
		}
		fclose($file);
		fclose($tempFile);
		// delete original and replace with temp
		rename($tempFileName, $this->getResourceFileName($name));
		if ($found){
            // return id and updated data without keys (for mapping)
			return array_merge([$id], array_values($data));
		} else {
			// todo handle better
			die('not found');
		}
	}

	public function delete($name, $id = null)
	{
		if (!$id){
			// delete all

			// get array of all ids deleted
			$deletedIds = [];
			$allData = $this->read($name);
			foreach ($allData as $row){
				$deletedIds[] = $row[0];
			}
			// delete and recreate resource file
			unlink($this->getResourceFileName($name));
			$this->createResourceFile($name);
			return $deletedIds;
		}
		// open file
		$file = fopen($this->getResourceFileName($name), 'r');
		// create temp file
		$tempFileName = __DIR__ . '/' . $this->getDataFolder() . 'temp.csv';
		$tempFile = fopen($tempFileName, 'w');
		// loop through file and write if doesn't match id
		$found = false;
		if ($file){
			while (! feof($file)){
				$row = fgetcsv($file);
				if ($row && $id !== (int) $row[0]){
					fputcsv($tempFile, $row);
				} elseif ($row) {
					$found = true;
				}
			}
		} else {
			// todo handle better
			die('no file');
		}
		fclose($file);
		fclose($tempFile);
		// delete original and replace with temp
		rename($tempFileName, $this->getResourceFileName($name));
		// return id
		if ($found){
			// todo decide what to return on successful delete
			return $id;
		} else {
			// todo handle better
			die('not found');
		}
	}

	/**
	 * @return bool
	 */
	private function resourceFileExists($name)
	{
		return file_exists($this->getResourceFileName($name));
	}

	/**
	 * @return string
	 */
	private function getResourceFileName($name){
		return __DIR__ . '/' . $this->getDataFolder() . $name  . self::FILE_FORMAT;
	}

	/**
	 * @descrption convenience function combining check and creation
	 */
	private function createResourceFileIfNotExists($name)
	{
		if (!$this->resourceFileExists($name)){
			$this->createResourceFile($name);
		}
	}

	/**
	 * @return bool
	 */
	private function createResourceFile($name)
	{
		return touch($this->getResourceFileName($name));
	}


	/**
	 * @return int
	 */
	public function getLastId($name)
	{
		$fileName = $this->getResourceFileName($name);
		$fileContents = file($fileName);
		
		// if no data return 0
		if ($fileContents === null){
			return 0; 
		}

		$lastRow = str_getcsv(array_pop($fileContents));
		$id = array_shift($lastRow);
		return (int) $id;
	}

	/**
	 * @return string
	 */
	private function getDataFolder()
	{
		return $this->dataFolder;
	}
}
