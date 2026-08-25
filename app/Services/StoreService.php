<?php

namespace App\Services;

use App\Http\Resources\BasicResources;
use App\Models\Store;
use App\Repositories\StoreRepoInterface;
use Illuminate\Http\Request;

class StoreService
{
    protected $storeInterface;

    public function __construct(StoreRepoInterface $storeRepoInterface)
    {
        $this->storeInterface = $storeRepoInterface;
    }

    public function index(Request $request)
    {
        $stores = Store::all();

        return BasicResources::make(null)->result($stores);
    }

    public function store($request)
    {
        $data = $request->validated();
        $this->storeInterface->store($data);

        return BasicResources::make(null)->result($data, 'Store created successfully');
    }

    public function show($id)
    {
        $store = $this->storeInterface->show($id);
        if (! $store) {
            return BasicResources::make(null)->error('Store not found', 404);
        }

        return BasicResources::make(null)->result($store);
    }

    public function update($request, $id)
    {
        $data = $request->validated();
        $store = $this->storeInterface->show($id);
        if (! $store) {
            return BasicResources::make(null)->error('Store not found', 404);
        }
        $this->storeInterface->update($data, $id);

        return BasicResources::make(null)->result($data, 'Store updated successfully');
    }

    public function destroy($id)
    {
        $store = $this->storeInterface->show($id);
        if (! $store) {
            return BasicResources::make(null)->error('Store not found', 404);
        }
        $this->storeInterface->delete($id);

        return BasicResources::make(null)->delete();
    }
}
