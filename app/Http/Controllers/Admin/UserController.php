<?php

namespace App\Http\Controllers\Admin;

use Json;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sortByElements = ["name asc" => "Name (A => Z)", "name desc" => "Name (Z => A)",
            "email asc" => "Email (A => Z)", "email desc" => "Email (Z => A)",
            "active asc" => "Not active", "admin desc" => "Admin"];
        $search = $request->input('sortBy') ?? "Name (A => Z)";
        $sortBy = array_search($search, $sortByElements);

        $pieces = explode(" ", $sortBy);
        $column = $pieces[0];
        $direction = $pieces[1];

        $nameOrEmail = '%' . $request->input('nameOrEmail') . '%';
        $users = User::with('orders')
            ->orderBy($column, $direction)
            ->where(function ($query) use ($nameOrEmail) {
                $query
                    ->where('name', 'like', $nameOrEmail);
            })
            ->orWhere(function ($query) use ($nameOrEmail) {
                $query
                    ->where('email', 'like', $nameOrEmail);
            })
            ->paginate(10)
            ->appends(['sortBy' => $request->input('sortBy'),
                'nameOrEmail' => $request->input('nameOrEmail')]);
        $result = ['users' => $users, 'sortByElements' => $sortByElements];
        Json::dump($result);
        return view('admin.users.index', $result);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('admin/users');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return redirect('admin/users');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return redirect('admin/users');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $result = compact('user');
        Json::dump($result);
        return view('admin.users.edit', $result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if ($user->id == auth()->user()->id) {
            $text = "In order not to exclude yourself from (the admin section of) the application, you cannot update your own profile!";
            session()->flash('danger', $text);
        }
        else {
            $this->validate($request, [
                'name' => 'required|min:3',
                'email' => 'required|email|unique:users,email,' . $user->id
            ]);
            $user->name = $request->name;
            $user->email = $request->email;
            $active = ($request->active == true) ? 1 : 0;
            $admin = ($request->admin == true) ? 1 : 0;
            $user->active = $active;
            $user->admin = $admin;
            $user->save();
            session()->flash('success', "The user <b>$user->name</b> has been updated!");
        }
        return redirect('admin/users');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        session()->flash('success', "The user <b>$user->name</b> has been deleted");
        return redirect('admin/users');
    }
    public function qyrUsers(User $user)
    {
        if ($user->id == auth()->user()->id) {
            $text = "In order not to exclude yourself from (the admin section of) the application, you cannot delete your own profile!";
            session()->flash('danger', $text);
        } else {
            $user->delete();
            session()->flash('success', "The user <b>$user->name</b> has been deleted");
        }
        return redirect('admin/users');
    }
}
