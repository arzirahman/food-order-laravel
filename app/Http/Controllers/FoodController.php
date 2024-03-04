<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Http\Requests\FoodRequest;
use App\Http\Requests\ToggleFavoriteRequest;
use App\Http\Resources\MessageResource;
use App\Models\Cart;
use App\Models\FavoriteFood;
use App\Models\Food;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FoodController extends Controller
{
    private function getData($user, $filterData, $paginationData = null)
    {
        $query = Food::query();
        $query->with(['category', 
            'favorite_food' => function ($query) use ($user) {
                $query->where('user_id', $user['user_id']);
            }, 
            'cart' => function ($query) use ($user) {
                $query->where('user_id', $user['user_id']);
            }
        ]);

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

        $totalData = $foods->total();

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
                'is_favorite' => count($food->favorite_food) > 0 && $food->favorite_food->first()['is_favorite'] ? true : false,
                'is_cart' => count($food->cart) > 0 ? true : false
            ];
        });

        return [
            'total' => $totalData,
            'data' => $transformedFoods
        ];
    }

    private function checkFood(int $foodId)
    {
        $food = Food::find($foodId);
        if (!$food) {
            throw ValidationException::withMessages(['food_id' => 'Food not found']);
        }
        return $food;
    }

    public function index(FoodRequest $request): Response
    {
        $paginationData = $request->paginationData();
        $filterData = $request->filterData();
        $user = $request->user;

        $foods = $this->getData($user, $filterData, $paginationData);

        return MessageResource::success(200, "Request Food List Success", $foods['data'], $foods['total']);
    }

    public function toggleFavorite(ToggleFavoriteRequest $request, $foodId): Response
    {
        $food = $this->checkFood($foodId);
        $user = $request->user;

        $existingFavorite = FavoriteFood::where('food_id', $foodId)
            ->where('user_id', $user['user_id'])
            ->first();

        if ($existingFavorite) {
            FavoriteFood::where('food_id', $foodId)
                ->where('user_id', $user['user_id'])
                ->update([
                    'is_favorite' => !$existingFavorite->is_favorite
                ]);
            
            $message = $food->food_name . (!$existingFavorite->is_favorite ? " added to " : " removed from ") . "favorites";
        } else {
            FavoriteFood::insert([
                'food_id' => $foodId,
                'user_id' => $user['user_id'],
                'is_favorite' => true
            ]);
            $message = "$food->food_name added to favorites";
        }

        return MessageResource::success(200, $message, null);
    }

    public function addCart(CartRequest $request) : Response
    {
        $foodId = $request->validated()['food_id'];
        $food = $this->checkFood($foodId);
        $user = $request->user;
        $cart = Cart::where('food_id', $foodId)->where('user_id', $user['user_id'])->first();
        if (!$cart)
        {
            Cart::create([
                'food_id' => $foodId,
                'user_id' => $user['user_id']
            ]);
        }
        $foods = $this->getData($user, ['foodId' => $foodId]);
        return MessageResource::success(200, "$food->food_name Added to Cart", $foods['data']->first(), $foods['total']);
    }

    public function removeCart(ToggleFavoriteRequest $request, $foodId) : Response
    {
        $food = $this->checkFood($foodId);
        $user = $request->user;
        Cart::where('user_id', $user['user_id'])->where('food_id', $food->food_id)->delete();
        $foods = $this->getData($user, ['foodId' => $food->food_id]);
        return MessageResource::success(200, "$food->food_name removed from Cart", $foods['data']->first(), $foods['total']);
    }
}
