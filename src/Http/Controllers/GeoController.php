<?php

namespace Mdhesari\LaravelCities\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Mdhesari\LaravelCities\Models\Geo;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GeoController extends Controller
{
    // [Geo] Get an item by $id
    public function item($id)
    {
        $geo = Geo::findOrFail($id);

        $this->applyFilter($geo);

        return api()->success(null, [
            'item' => $geo,
        ]);
    }

    // [Collection] Get multiple items by ids (comma seperated string or array)
    public function items($ids = [])
    {
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }

        $items = Geo::getByIds($ids);

        if (count($items) < 1) {
            throw new NotfoundHttpException;
        }

        return api()->success(null, [
            'items' => $items,
        ]);
    }

    // [Collection] Get children of $id
    public function children($id)
    {
        $items = $this->applyFilter(Geo::findOrFail($id)->getChildren());

        return api()->success(null, [
            'items' => $items,
        ]);
    }

    // [Geo] Get parent of  $id
    public function parent($id)
    {
        $geo = Geo::findOrFail($id)->getParent();

        $this->applyFilter($geo);

        return api()->success(null, [
            'item' => $geo,
        ]);
    }

    // [Geo] Get country by $code (two letter code)
    public function country($code)
    {
        $geo = Geo::getCountry($code);

        $this->applyFilter($geo);

        return api()->success(null, [
            'item' => $geo,
        ]);
    }

    // [Collection] Get all countries
    public function countries()
    {
        return api()->success(null, [
            'items' => $this->applyFilter(Geo::level(Geo::LEVEL_COUNTRY)->get()),
        ]);
    }

    // [Collection] Search for %$name% in 'name' and 'alternames'. Optional filter to children of $parent_id
    public function search($name, $parent_id = null)
    {
        if ($parent_id) {
            $items = $this->applyFilter(Geo::searchNames($name, Geo::findOrFail($parent_id)));
        } else {
            $items = $this->applyFilter(Geo::searchNames($name));
        }

        return api()->success(null, [
            'items' => $items,
        ]);
    }

    public function ancestors($id)
    {
        $current = Geo::find($id);
        $ancestors = $current->ancenstors()->get()->sortBy('a1code')->values();
        $ancestors->push($current);

        $result = collect();
        foreach ($ancestors as $i => $ancestor) {
            if ($i === 0) {
                $locations = Geo::getCountries();
            } else {
                $parent = $ancestor->getParent();
                if (!$parent) {
                    continue;
                }

                $locations = $parent->getChildren();
            }

            $selected = $locations->firstWhere('id', $ancestor->id);
            $selected && $selected->isSelected = true;
            $result->push($locations);

            if ($i == $ancestors->count() - 1 && $ancestor) {
                $childrens = $ancestor->getChildren();
                if ($childrens->count()) {
                    $result->push($childrens);
                }
            }
        }

        $result = $this->applyFilter($result);

        return api()->success(null, [
            'items' => $result,
        ]);
    }

    public function breadcrumbs($id)
    {
        $current = Geo::find($id);
        $ancestors = $current->ancenstors()->get();
        $ancestors->push($current);

        $ancestors = $this->applyFilter($ancestors);

        return api()->success(null, [
            'items' => $ancestors,
        ]);
    }

    // Apply Filter from request to json representation of an item or a collection
    // api/call?fields=field1,field2
    protected function applyFilter($geo)
    {
        if (request()->has('fields')) {
            if (get_class($geo) == Collection::class) {
                foreach ($geo as $item) {
                    $this->applyFilter($item);
                }

                return $geo;
            }

            $fields = request()->input('fields');
            if ($fields == 'all') {
                $geo->fliterFields();
            } else {
                $fields = explode(',', $fields);
                array_walk($fields, function (&$item) {
                    $item = strtolower(trim($item));
                });
                $geo->fliterFields($fields);
            }
        }

        return api()->success(null, [
            'item' => $geo,
        ]);
    }
}
