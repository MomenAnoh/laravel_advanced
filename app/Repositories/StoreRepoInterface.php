<?php

namespace App\Repositories;

use Illuminate\Http\Request;

interface StoreRepoInterface
{
    public function index(Request $request);

    public function store(Request $request);

    public function show($id);

    public function update($data, $id);

    public function delete($id);
}
