<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    
    public function getAll()
    {
        return Store::all();
    }

    public function add(Request $request)
    {

        $user_id = auth()->user()->id;

        $validator = Validator::make($request->all(), [
            'name' => 'string|required',
            'location' => 'string|required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $store = Store::create([
            'user_id' => $user_id,
            'name' => $request->name,
            'location' => $request->location,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Store added successfully",
            'data' => $store
        ]);
    }

    public function getMyStores()
    {

        $stores = User::with('store')->find(auth()->user()->id);
        return response()->json([
            'success' => true,
            'data' => $stores
        ]);
    }

    public function getStoreById(int $id)
    {
        $user = auth()->user();
        $store = $user->store->find($id);
        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => "Store not found for this user",
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $store
        ]);
    }

    public function edit(Request $request, $id)
    {
        $storeResponse = $this->getStoreById($id);
        if (!$storeResponse->original['success']) {
            return $storeResponse;
        }
        $store = $storeResponse->original['data'];

        $validator = Validator::make($request->all(), [
            'name' => 'string|required',
            'location' => 'string|required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }
        $store->update([
            'name' => $request->name,
            'location' => $request->location,
        ]);
        return response()->json([
            'success' => true,
            'data' => $store
        ]);
    }

    public function delete($id)
    {
        $storeResponse = $this->getStoreById($id);
        if (!$storeResponse->original['success']) {
            return $storeResponse;
        }
        $storeResponse->original['data']->delete();
        return response()->json([
            'success' => true,
            'message' => 'store deleted successfully',
        ]);
    }
}
