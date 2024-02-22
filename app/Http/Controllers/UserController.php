<?php

namespace App\Http\Controllers;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /*** Display a listing of the resource. */
    public function index()
    {
        $users = User::latest()->with(['address'])->get();
        return view('welcome', compact('users'));
    }
    
    /*** Show the form for creating a new resource. */
    public function create()
    {
        return view('registration');
    }

    /*** Store a newly created resource in storage. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'inputName' => 'required|string|max:255',
            'inputAddress1' => 'required|string|max:255',
            'contactNo' => 'required|digits:10',
        ]);
        
        $name = request('inputName');
        $address1 = request('inputAddress1');
        $address2 = request('inputAddress2');
        $contactno = request('contactNo');
        
        $record= new User();
        $record->name = $name;
        $record->contact = $contactno;
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

    /*** Show the form for editing the specified resource. */
    public function edit(string $userid)
    {   
        $user = User::find($userid);
        return view('edit', compact('user'));
    }

    /*** Update the specified resource in storage. */
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

    /*** Remove the specified resource from storage. */
    public function destroy(string $user)
    {
        User::find($user)->delete();
        Address::where('user_id',$user)->delete();
        return response()->json(['success'=>'User Deleted Successfully!']);
    }
}
