<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Json;

class User2Controller extends Controller
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
        return view('admin.users2.index', $result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('admin/users2');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|min:3|unique:users,name'
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->save();
        return response()->json([
            'type' => 'success',
            'text' => "The user <b>$user->name</b> has been added"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return redirect('admin/users2');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return redirect('admin/users2');

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
        $this->validate($request,[
            'name' => 'required|min:3|unique:users,name,' .$user->id,
            'email' => 'required|email|unique:users,email,' .$user->id
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $active = ($request->active == true) ? 1 : 0;
        $admin = ($request->admin == true) ? 1 : 0;
        $user->active = $active;
        $user->admin = $admin;


        $user->save();

        return response()->json([
            'type' => 'success',
            'text' => "The user <b>$user->name</b> has been updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->id == auth()->user()->id) {
            return response()->json([
                'type' => 'error',
                'text' => "In order not to exclude yourself from (the admin section of) the application, you cannot update your own profile!"
            ]);
        }else{
            $user->delete();
            return response()->json([
                'type' => 'success',
                'text' => "The user <b>$user->name</b> has been deleted"
            ]);
        }
    }
}
