<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;
    public function index(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);
        // get users for management page
        $users = User::whereIn('role', ['user', 'kaprodi'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->paginate($limit);

        return $this->successPaginationResponse(

            $users->items(),
            [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
            ],
            'Users retrieved successfully',

        );
    }
}
