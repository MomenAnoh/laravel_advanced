<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Services\StoreService;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    protected $storeService;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function index(Request $request)
    {
        return $this->storeService->index($request);
    }

    public function store(StoreRequest $request)
    {
        return $this->storeService->store($request);
    }

    public function show($id)
    {
        return $this->storeService->show($id);
    }

    public function update(StoreRequest $request, $id)
    {
        return $this->storeService->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->storeService->destroy($id);
    }
}
