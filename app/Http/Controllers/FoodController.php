<?php

namespace App\Http\Controllers;

use App\Http\Requests\FoodRequest;
use App\Http\Requests\ToggleFavoriteRequest;
use App\Http\Resources\MessageResource;
use App\Models\FavoriteFood;
use App\Models\Food;
use Illuminate\Http\Response;

class FoodController extends Controller
{
    private function getData($user, $filterData, $paginationData)
    {
        $query = Food::query();
        $query->with(['category', 'cart' => function ($query) use ($user) {
            $query->where('user_id', $user['user_id']);
        }]);
        $query->leftJoin('favorite_foods', function ($join) use ($user) {
            $join->on('foods.food_id', '=', 'favorite_foods.food_id')
                ->where('favorite_foods.user_id', '=', $user['user_id']);
        });

        if (isset($filterData['foodId'])) {
            $query->where('foods.food_id', $filterData['foodId']);
        }

        if (isset($filterData['foodName'])) {
            $query->where('food_name', 'ilike', '%' . $filterData['foodName'] . '%');
        }

        if (isset($filterData['categoryId'])) {
            $query->where('category_id', $filterData['categoryId']);
        }

        if (isset($paginationData['sortBy'])) {
            $sortBy = $paginationData['sortBy'];
            $query->orderBy("food_name", $sortBy['field'] == "foodName" ? $sortBy['direction'] : 'asc');
        }

        if (isset($paginationData['pageSize']) && isset($paginationData['pageNumber'])) {
            $foods = $query->paginate($paginationData['pageSize'], ['*'], 'page', $paginationData['pageNumber']);
        } else {
            $foods = $query->paginate(10, ['*'], 'page', 1);
        }
        
         /** @var \Illuminate\Pagination\LengthAwarePaginator $foods */
        $transformedFoods = $foods->map(function ($food) {
            return [
                'food_id' => $food->food_id,
                'food_name' => $food->food_name,
                'price' => $food->price,
                'image_filename' => $food->image_filename,
                'category' => [
                    'category_id' => $food->category->category_id,
                    'category_name' => $food->category->category_name,
                ],
                'is_favorite' => $food->is_favorite ?? false,
                'is_cart' => count($food->cart) > 0 ? true : false
            ];
        });

        return $transformedFoods;
    }

    public function index(FoodRequest $request): Response
    {
        $paginationData = $request->paginationData();
        $filterData = $request->filterData();
        $user = $request->user;

        $foods = $this->getData($user, $filterData, $paginationData);

        return MessageResource::success(200, "Request Food List Success", $foods);
    }

    public function toggleFavorite(ToggleFavoriteRequest $request, $foodId): Response
    {
        $food = Food::find($foodId);
        if (!$food) {
            return MessageResource::success(400, "Food Id not found", null);
        }

        $user = $request->user;

        $existingFavorite = FavoriteFood::where('food_id', $foodId)
            ->where('user_id', $user['user_id'])
            ->first();

        if ($existingFavorite) {
            $existingFavorite->is_favorite = !$existingFavorite->is_favorite;
            $existingFavorite->save();
            $message = $food->food_name . ($existingFavorite->is_favorite ? " added to " : " removed from ") . "favorites";
        } else {
            FavoriteFood::create([
                'food_id' => $foodId,
                'user_id' => $user['user_id'],
                'is_favorite' => true
            ]);
            $message = "$food->food_name added to favorites";
        }

        $food = $this->getData($user, ["foodId" => $foodId], null)->first();

        return MessageResource::success(200, $message, $food);
    }
}
