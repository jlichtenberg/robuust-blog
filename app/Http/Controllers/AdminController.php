<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Blog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class AdminController extends Controller
{
    /**
     * Show the login page.	
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        return view('admin.login');
    }


    /**
     * Log in the user.
     * 
     * @param \App\Http\Requests\LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        // Check if the user exists
        $user = User::where('email', $request->email)->first();

        // Check if the user exists and the password is correct
        if ($user && Hash::check($request->password, $user->password)) {
            // Check if the user is an admin if not return an error
            if(!$user->admin) {
                return back()->withErrors([
                    'email' => 'De gebruikersgegevens zijn onjuist.',
                ]);
            }
            // Log in the user
            auth()->login($user);
            $request->session()->regenerate();

            // Redirect the user to the dashboard
            return redirect()->intended('admin/dashboard');
        }

        // Return an error if the user does not exist or the password is incorrect
        return back()->withErrors([
            'email' => 'De gebruikersgegevens zijn onjuist.',
        ]);
    }

    /**
     * Show the dashboard.
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function dashboard($month = null): View
    {
        $currentMonthName = now()->format('F');
        $currentMonth = now()->startOfMonth();
        if($month) {
            $currentMonthName = $month;
            $currentMonth = Carbon::createFromFormat('F', $month)->startOfMonth();
        }

        // i want returned the count of blogs and the count of users created from the month provided
        $userCount = User::whereYear('created_at', $currentMonth->year)
                 ->whereMonth('created_at', $currentMonth->month)
                 ->count();
        
        $blogsCount = Blog::whereYear('created_at', $currentMonth->year)
                 ->whereMonth('created_at', $currentMonth->month)
                 ->count();
        
        $blogsYear = Blog::selectRaw('count(*) as count, monthname(created_at) as month')
            ->where('created_at', '>', now()->subMonths(12))
            ->groupBy('month')
            ->get();

        return view('admin.dashboard', compact(['blogsYear', 'currentMonthName', 'userCount', 'blogsCount']));
    }

    /**
     * Show the blogs.
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function blogs(): View
    {
        $blogs = Blog::all();
        return view('admin.blogs', compact('blogs'));
    }

    /**
     * Show the blog.
     * 
     * @param int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function showBlog(int $id): View
    {
        $blog = Blog::with('user')->findOrFail($id);
        return view('admin.blog-show', compact('blog'));
    }

    /**
     * Show the Users
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function users(): View
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    /**
     * Log out the user.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        // Log out the user
        auth()->logout();

        return redirect('admin/login');
    }
}
