<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //here all crud logics 

    public function loadAllUsers(){
        $all_users = User::all();
        return view('users',compact('all_users'));
    }

    public function loadAddUserForm(){
        return view('add_user');
    }

    public function AddUser(Request $request){
        //form validation
        $request->validate([
            'fullname' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'password' => 'required|confirmed|min:4|max:8',
        ]);

        // register user
        try {
            $new_user = new User;
            $new_user->name = $request->fullname;
            $new_user->email = $request->email;
            $new_user->phone_number = $request->phone;
            $new_user->password = Hash::make($request->password);
            $new_user->save();

            return redirect('/users')->with('success', 'User added successfully!');

        } catch (\Exception $e) {
            return redirect('/add/user')->with('fail', $e->getMessage());
        }
    }

    public function EditUser(Request $request){
        //form validation
        $request->validate([
            'fullname' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
        ]);

        // update user
        try {
            $update_user = User::where('id',$request->user_id)->update([
                'name' => $request->fullname,
                'email' => $request->email,
                'phone_number' => $request->phone,
            ]);
            return redirect('/users')->with('success', 'User updated successfully!');

        } catch (\Exception $e) {
            return redirect('/edit/user')->with('fail', $e->getMessage());
        }
    }

    public function loadEditForm($id){
        $user = User::find($id);
        return view('edit_user', compact('user'));
    }

    public function deleteUser($id){
        try {
            User::where('id',$id)->delete();
            return redirect('/users')->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            return redirect('/users')->with('fail', $e->getMessage());
        }
    }
}
