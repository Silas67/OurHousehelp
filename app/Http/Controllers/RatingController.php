<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function storeRating(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $existingRating = Rating::where('user_id', $id)->where('rated_by', Auth::id())->first();

        if ($existingRating) {
            // Update existing rating
            $existingRating->update([
                'rating' => $request->input('rating'),
                'comment' => $request->input('comment'),
            ]);
        } else {
            // Create new rating
            Rating::create([
                'user_id' => $id,
                'rated_by' => Auth::id(),
                'rating' => $request->input('rating'),
                'comment' => $request->input('comment'),
            ]);
        }

        // Recalculate the average rating and total count
        $ratings = Rating::where('user_id', $id)->get();
        $averageRating = $ratings->avg('rating');
        $totalCount = $ratings->count();

        return response()->json([
            'success' => true,
            'averageRating' => $averageRating,
            'totalCount' => $totalCount
        ]);
    }
}
