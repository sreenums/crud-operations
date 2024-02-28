<?php

namespace App\Http\Controllers;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    public function index()
    {
        $users = User::getUsersExcludingAdminWithAddress();
        return view('welcome', compact('users'));
    }
    
    public function create()
    {
        return view('registration');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'inputName' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'inputAddress1' => 'required|string|max:255',
            'contactNo' => 'required|digits:10',
            'password' => 'required|min:8',
        ], [
            'email.unique' => 'The email address has already been taken.',
        ]);

        $name = request('inputName');
        $emailId = request('email');
        $address1 = request('inputAddress1');
        $address2 = request('inputAddress2');
        $contactno = request('contactNo');
        $password = request('password');

        $record= new User();
        $record->name = $name;
        $record->email = $emailId;
        $record->contact = $contactno;
        $record->password = bcrypt($password);
        $record->save();
        
        if($address1){
            $addres1 = new Address();
            $addres1->address = $address1;
            $addres1->user_id = $record->id;
            $addres1->save();
        }
        if($address2){
            $addres = new Address();
            $addres->address = $address2;
            $addres->user_id = $record->id;
            $addres->save();
        }
        $record->address_id = $addres1->id;
        $record->save();

        return back()->withSuccess('User added successfully!');
    }

    /*** Display the specified resource. */
    public function show(string $id)
    {
        //
    }

    public function edit(string $userid)
    {   
        $user = User::find($userid);
        return view('edit', compact('user'));
    }

    public function update(Request $request, $userId)
    {   
        
        $validated = $request->validate([
            'inputName' => 'required|string|max:255',
            'inputAddress' => 'required|string|max:255',
            'contactNo' => 'required|max:10',
        ]);

        $name = request('inputName');
        $address = request('inputAddress');
        $newAddress = request('newAddress');
        $contactno = request('contactNo');

        $record = User::find($userId);
        $record->name = $name;
        $record->contact = $contactno;
        $record->save();

        $addressId = $record->address_id;
        if($address){
            $addressRecord = Address::find($addressId);
            $addressRecord->address = $address;
            $addressRecord->save();
        }
        if($newAddress){
            $addres = new Address();
            $addres->address = $newAddress;
            $addres->user_id = $userId;
            $addres->save();

            $record->address_id = $addres->id;  //Update New address as latest address
            $record->save();
        }

        return back()->withSuccess('User updated successfully!');
    }

    public function destroy(string $user)
    {
        Address::where('user_id',$user)->delete();
        User::find($user)->delete();
        return response()->json(['success'=>'User Deleted Successfully!']);
    }
}
