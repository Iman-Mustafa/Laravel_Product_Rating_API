<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\UserRating;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    /**
     * Rate a product or update existing rating if already rated by this user.
     */
    public function rateProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'    => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $userRating = UserRating::updateOrCreate(
            [
                'user_id'    => $request->user_id,
                'product_id' => $request->product_id,
            ],
            [
                'rating'          => $request->rating,
                'rating_datetime' => Carbon::now(),
            ]
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Product rated successfully',
            'data'    => $userRating
        ], 200);
    }

    /**
     * Change an existing rating.
     */
    public function changeRating(Request $request)
    {
        return $this->rateProduct($request);
    }

    /**
     * Remove a user rating for a product.
     */
    public function removeRating(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'    => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $rating = UserRating::where('user_id', $request->user_id)
            ->where('product_id', $request->product_id)
            ->first();

        if (!$rating) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Rating record not found'
            ], 404);
        }

        $rating->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Rating removed successfully'
        ], 200);
    }

    /**
     * Display a list of products with calculated rating metrics.
     */
    public function listProducts(Request $request)
    {
        $requestingUserId = $request->query('user_id');
        $now = Carbon::now();

        $products = Product::with('ratings')->get()->map(function ($product) use ($requestingUserId, $now) {
            $averageRating = $product->ratings->avg('rating');
            $averageRatingFormatted = $averageRating !== null ? round((float)$averageRating, 2) : 0;

            $currentUserRating = null;
            $timePassed = null;
            $activeTime = 'inactive';

            if ($requestingUserId) {
                $userRatingRecord = $product->ratings->where('user_id', $requestingUserId)->first();

                if ($userRatingRecord) {
                    $currentUserRating = $userRatingRecord->rating;

                    if ($userRatingRecord->rating_datetime) {
                        $ratingDatetime = Carbon::parse($userRatingRecord->rating_datetime);
                        $timePassed = (int)$ratingDatetime->diffInMinutes($now);
                        $activeTime = ($timePassed > 30) ? 'active' : 'inactive';
                    }
                }
            }

            return [
                'id'          => $product->id,
                'name'        => $product->name,
                'description' => $product->description,
                'price'       => $product->price,
                'ratings'     => $averageRatingFormatted,
                'user_rating' => $currentUserRating,
                'time_passed' => $timePassed !== null ? $timePassed . ' minutes' : null,
                'active_time' => $activeTime,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => $products
        ], 200);
    }
}
