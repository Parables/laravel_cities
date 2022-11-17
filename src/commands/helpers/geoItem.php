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

    public function __construct($rawData, $geoItems, $translations)
    {
        $rawData[3] = json_encode(str_getcsv($rawData[3]), JSON_UNESCAPED_UNICODE);
        $this->data = $rawData;
        $this->geoItems = $geoItems;
        $this->name_en = $translations[$this->data[0]]['en'] ?? null;
        $this->name_ua = $translations[$this->data[0]]['uk'] ?? null;
        $this->name_ru = $translations[$this->data[0]]['ru'] ?? null;
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
}
