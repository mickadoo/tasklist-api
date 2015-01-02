<?php
namespace MichaelDevery\Tasklist\Library\Adapters;

use MichaelDevery\Tasklist\Library\ApiException;

class CsvAdapter implements AdapterInterface
{

	const FILE_FORMAT = '.csv';

	/** @var string */
	protected $dataFolder = 'csv_data/';

	/**
	 * @param string $name
	 * @param array $data
	 * @param array $fields
	 * @return array
	 */
	public function create($name, $data, $fields = [])
	{
		$this->createResourceFileIfNotExists($name);
		$fileName = $this->getResourceFileName($name);
		
		// get last id
		$id = $this->getLastId($name);
		$newId = $id + 1;
		// open file for appending

		if (@!fopen($fileName,'a')){
			throw new ApiException(500, 'Could not open resource file for ' . $name);
		}
		$handle = fopen($fileName,'a');
			// merge new id with data while un-setting old array keys (for mapping)
		$data = array_merge(['id' => $newId], $data);

		$data = $this->getOrderedArray($data, $fields);

		// write data to end of file
		fputcsv($handle,$data);
		// save and return new id
		fclose($handle);
		return $data;
	}

	/**
	 * @param $name
	 * @param null $id
	 * @param array $fields
	 * @return array
	 * @throws ApiException
	 */
	public function read($name, $id = null, $fields = [])
	{
		if (!$this->resourceFileExists($name)){
			throw new ApiException(500, "Resource file cannot be found or created for $name");
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
		if ($id){
			// if it got this far then row matching ID wasn't found
			throw new ApiException(404, "No $name found with id: $id");
		}
        return $results;
	}

	/**
	 * @param string $name
	 * @param int $id
	 * @param array $data
	 * @param array $fields
	 * @return array
	 * @throws ApiException
	 */
	public function update($name, $id, $data, $fields = [])
	{
		// open file
		$file = fopen($this->getResourceFileName($name), 'r');
		$updatedData = [];

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
					// get array of updated data without destroying existing row
					$updatedData = $this->getUpdatedRow($row, $data, $fields);
					fputcsv($tempFile, $updatedData);
					$found = true;
				}
			}
		} else {
			throw new ApiException(500, "Resource file cannot be found or created for $name");
		}
		fclose($file);
		fclose($tempFile);
		// delete original and replace with temp
		rename($tempFileName, $this->getResourceFileName($name));
		if ($found){
            // return id and updated data without keys (for mapping)
			return $updatedData;
		} else {
			throw new ApiException(404, "No $name found with id: $id");
		}
	}

	/**
	 * @param string $name
	 * @param int $id
	 * @return array|int|null
	 * @throws ApiException
	 */
	public function delete($name, $id = null)
	{
		$id = (int) $id;

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
			throw new ApiException(500, "Resource file cannot be found or created for $name");
		}
		fclose($file);
		fclose($tempFile);
		// delete original and replace with temp
		rename($tempFileName, $this->getResourceFileName($name));
		// return id
		if ($found ==  true){
			return $id;
		} else {
			throw new ApiException(404, "No $name found with id: $id");
		}
	}

	/**
	 * @param array $oldRow
	 * @param array $newFieldData
	 * @param array $fieldNames
	 * @return array
	 * @description creates associative array using field names and returns original row with updated field that are
	 * set in the newFieldData only changed
	 */
	public function getUpdatedRow($oldRow, $newFieldData, $fieldNames)
	{
		$assocRow = array();
		foreach ($oldRow as $key => $current){
			if (isset($fieldNames[$key])) {
				$assocRow[$fieldNames[$key]] = $current;
			}
		}
		return $this->getOrderedArray(array_merge($assocRow, $newFieldData), $fieldNames);
	}

	/**
	 * @param $data
	 * @param $fields
	 * @return array
	 */
	public function getOrderedArray($data, $fields)
	{
		$result = array();

		foreach ($fields as $field){
			if (isset($data[$field])){
				$result[] = $data[$field];
			}
		}
		return $result;
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
	 * @param string $name
	 * @throws ApiException
	 * @description convenience function combining check and creation
	 */
	private function createResourceFileIfNotExists($name)
	{
		if (!$this->resourceFileExists($name)){
			if (!$this->createResourceFile($name)){
				throw new ApiException(500, "Couldn't create CSV storage file for $name");
			}
		}
	}

	/**
	 * @return bool
	 */
	private function createResourceFile($name)
	{
		touch($this->getResourceFileName($name));
		chmod($this->getResourceFileName($name), 0770);
		chown($this->getResourceFileName($name), get_current_user());
		return true;
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
