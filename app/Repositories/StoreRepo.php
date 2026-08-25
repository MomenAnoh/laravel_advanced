<?php

namespace App\Repositories;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreRepo implements StoreRepoInterface
{
    public function index(Request $request)
    {
        return Store::all();
    }

    public function store($data)
    {
        Store::create($data);
    }

    public function show($id)
    {
        return Store::find($id);
    }

    public function update($data, $id)
    {
        $store = Store::find($id);
        $store->update($data);
    }

    public function delete($id)
    {
        Store::find($id)->delete();
    }
}
