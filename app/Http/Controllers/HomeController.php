<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return !Auth::check() ? view('auth.login') : $this->redirect();
    }

    /**
     * Redirect to the default route based on the user role.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function redirect()
    {
        if (auth()->user()->role_id === Role::USER) {
            return redirect()->route('user.prescription.index');
        }

        if (auth()->user()->role_id === Role::PHARMACY) {
            return redirect()->route('pharmacy.prescription.index');
        }
    }
}
