<?php

namespace Igaster\LaravelCities\commands\helpers;

class geoItem
{
    public $data;

    public $name_en;
    public $name_ua;
    public $name_ru;

    public $parentId = null;
    public $childrenGeoId = [];
    public $depth = 0;

    public $left = null;
    public $right = null;

    private $geoItems;

    public function __construct($rawData, $geoItems)
    {
        $rawData[3] = json_encode(str_getcsv($rawData[3]), JSON_UNESCAPED_UNICODE);
        $this->data = $rawData;
        $this->geoItems = $geoItems;
        $this->fillTranslate($this->data[0]);
    }

    public function getId()
    {
        return $this->data[0];
    }

    public function getName()
    {
        return $this->data[2];
    }

    public function setParent($geoId)
    {
        if ($parent = $this->geoItems->findGeoId($geoId)) {
            $this->parentId = $geoId;
        }
    }

    public function addChild($geoId)
    {
        $this->childrenGeoId[] = $geoId;
    }

    public function getChildren()
    {
        $results = [];
        foreach ($this->childrenGeoId as $geoId) {
            $results[] = $this->geoItems->findGeoId($geoId);
        }
        return $results;
    }

    public function fillTranslate($id)
    {
        $fileName = storage_path('geo/alternateNamesV2.txt');
        $filesize = filesize($fileName);
        $handle = fopen($fileName, 'r');
        $count = 0;

        while (($line = fgets($handle)) !== false) {
            if (!$line || $line === '' || strpos($line, '#') === 0 || $line[0] != $id) {
                continue;
            }

            switch ($line[2]) {
                case 'en':
                    $this->name_en = $line[4];
                    break;
                case 'uk':
                    $this->name_ua = $line[4];
                    break;
                case 'ru':
                    $this->name_ru = $line[4];
                    break;
            }

            if ($this->name_ru != null && $this->name_ua != null && $this->name_en != null) {
                break;
            }
        }
    }
}
