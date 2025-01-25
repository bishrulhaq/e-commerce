<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Example: Gather some quick stats or anything relevant
        $stats = [
            'totalUsers' => User::count(),
            'totalOrders' => Order::count(),
            'totalProducts' => Product::count(),
        ];

        // Render the Vue page at resources/js/Pages/Admin/Dashboard/Index.vue
        return Inertia::render('Admin/Dashboard/Index', [
            'title' => 'Admin Dashboard',
            'stats' => $stats,
        ]);
    }
}
