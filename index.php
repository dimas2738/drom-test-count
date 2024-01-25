<?php

class File
{
    public function __construct(
        public string $path

    ) {}

    /**
     * @return array
     */
    public function getAllPath(): array
    {
        $allPath = [];
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->path, \RecursiveDirectoryIterator::SKIP_DOTS),
        );

        foreach ($files as $file) {
            if ($file->getFilename() === 'count') {
                $allPath[] = $file->getRealPath();
            }
        }
        return $allPath;
    }

    /**
     * @param array $allPath
     * @return array
     */
    public function getSumArrayByPath(array $allPath): array
    {
        $sum = [];
        foreach ($allPath as $path){
            if (!file_exists($path)) {
                echo "Странно, но Файл 'count' по пути {$path} отсутствует.";
            }
            $textFromFile = @file_get_contents($path);
            if ($textFromFile === false) {
                echo "Файл по пути {$path} не возможно открыть.";
            }
            $sum[]  =  array_sum($this->parseOnlyNumbers($textFromFile));
        }
        return $sum;
    }

    /**
     * @param string $path
     * @return array
     */
    private function parseOnlyNumbers(string $path): array
    {
        preg_match_all('/-?\d+/', $path, $numbersArr);
        return array_map('intval', $numbersArr[0]);
    }

    /**
     * @param array $data
     * @return int
     */
    public function getCountValuesSum(array $data) :int
    {
        return array_sum($data);
    }

    /**
     * @return string
     */
    public function getResult(): string
    {
        $allPathes= $this->getAllPath();
        $sumArrayByPath = $this->getSumArrayByPath($allPathes);
        $allValuesSum = $this->getCountValuesSum($sumArrayByPath);
        return "Сумма всех чисел в файлах из директории '{$this->path}' = {$allValuesSum}.";
    }

}

$path =  __DIR__ . '\test_data';
$data = new File($path);
echo $data->getResult();


