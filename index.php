<?php
class Duplicates {
	
	private $fileToWrite;  // Файл, в который записываются результаты
	private $directions;   // Путь, который будет проверяться
	
	public function __construct($path = null, $fileToWrite = null) {
		if (!$path) {
			$path = getcwd();
		} else if (!file_exists($path)) die('Директория не существует. Проверьте путь для поиска');
		
		$this->directions  = array();
		array_push($this->directions, $path);
		
		if (!$fileToWrite) {
			$fileToWrite = getcwd().DIRECTORY_SEPARATOR.'result.txt';
		}
		if (file_exists($fileToWrite)) {
			unlink($fileToWrite);
		}
		$this->fileToWrite = $fileToWrite;
	}
	
	public function searchForDuplicates() {
		$duplicates = array();	
		while(!empty($this->directions)) {
			$currentDir = array_pop($this->directions);
			foreach(scandir($currentDir) as $name) {
				if (strcmp($name, '.') != 0 && strcmp($name, '..') != 0) {
					$currentName = $currentDir.DIRECTORY_SEPARATOR.$name;
					if (is_dir($currentName)) {
						array_push($this->directions, $currentName);
					} else {
						$duplicates[$name][] = $currentDir;
					}
				}
			}
		}
		
		if (empty($duplicates)){
			die('Дубликатов не найдено');
		}else {
			$this->writeDuplicatesToFile($duplicates);
		}
	}
	
	protected function writeDuplicatesToFile($duplicates = []) {
		foreach($duplicates as $name => $pathArr) {
			if (count($duplicates[$name]) == 1) {
				unset($duplicates[$name]);
			} else {
				foreach($pathArr as $path) {
					$result = @file_put_contents($this->fileToWrite, $path.DIRECTORY_SEPARATOR.$name.PHP_EOL, FILE_APPEND);	
				}
				file_put_contents($this->fileToWrite, PHP_EOL, FILE_APPEND);
			}
		}	
	}
	
	public function getfileToWrite() {
		return $this->fileToWrite;
	}
}




try {

$pathToCheckDuplicates = "INSERT PATH FOR SEARCH HERE"; // Путь который будет проверяться
$dir = new Duplicates($pathToCheckDuplicates);
$dir->searchForDuplicates();
die('Поиск был выполнен успешно. Результаты находятся в файле: '.$dir->getfileToWrite());
} catch (Exception $e) {
die($e->getMessage());
}
?>