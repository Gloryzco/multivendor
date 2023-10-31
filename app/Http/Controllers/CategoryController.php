<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{

    public function storeImageToPath($image){
        $filename = time() . '_' . $image->getClientOriginalName();
        $path = $image->storeAs('public/categories', $filename);
        return $path;
    }

    public function validateImageRequest($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'string|unique:categories|required',
                'status' => 'string|in:available,removed|required',
                'image' => 'required|mimes:jpeg,jpg,png|max:2048',
            ],
            [
                'status.in' => 'Invalid status, expects available or removed',
            ]
        );

        if ($validator->fails()) {
            return $this->failureResponse($validator->errors(), 422);
        }
        return $this->successResponse('Success');
    }

    public function add(Request $request)
    {
        $validatorResponse = $this->validateImageRequest($request);
        if (!$validatorResponse->original['success']) {
            return $validatorResponse;
        }

        try {
            $path = $this->storeImageToPath($request->image);
            // dd($request->all());
            $category = Category::create([
                'status' => $request->status,
                'image_path' => $path,
                'name' => $request->name,
            ]);

            return $this->successResponse('Image successfully uploaded', $category);
        } catch (\Exception $e) {
            return $this->manageError($e, __METHOD__);
        }
    }

    public function edit(Request $request, int $id)
    {
        $validatorResponse = $this->validateImageRequest($request);
        if (!$validatorResponse->original['success']) {
            return $validatorResponse;
        }
        // dd($request->all());
        try {
            $getCategory = Category::find($id);
            if (!$getCategory) {
                return $this->failureResponse('Category not found');
            }
            $path = $this->storeImageToPath($request->image);
            $getCategory->update([
                'name' => $request->name,
                'status' => $request->status,
                'image_path' => $path,
            ]);
            return $this->successResponse('Category successfully updated', $getCategory);
        } catch (\Exception $e) {
            return $this->manageError($e, __METHOD__);
        }
    }

    public function delete(int $id){
        $category = Category::find($id);
        if (!$category) {
            return $this->failureResponse('Category not found');
        }
        $category->delete();
        return $this->successResponse('Category successfully deleted');
    }
}
