<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function addUser(Request $request)
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
            $addres = new Address();
            $addres->address = $address1;
            $addres->user_id = $record->id;
            $addres->save();
        }
        if($address2){
            $addres = new Address();
            $addres->address = $address2;
            $addres->user_id = $record->id;
            $addres->save();
        }

        return back()->withSuccess('User added successfully!');
    }

    public function modifyUser(Request $request,$userid)
    {
        
        $validated = $request->validate([
            'inputName' => 'required|string|max:255',
            'inputAddress' => 'required|string|max:255',
            'contactNo' => 'required|max:10',
        ]);

        $name = request('inputName');
        $address = request('inputAddress');
        $addressId = request('addressId');
        $newAddress = request('newAddress');
        $contactno = request('contactNo');

        $record = User::find($userid);
        $record->name = $name;
        $record->contact = $contactno;
        $record->save();

        if($address){
            $addressRecord = Address::find($addressId);
            $addressRecord->address = $address;
            $addressRecord->save();
        }
        if($newAddress){
            $addres = new Address();
            $addres->address = $newAddress;
            $addres->user_id = $userid;
            $addres->save();
        }

        return back()->withSuccess('User updated successfully!');
    }

    public function deleteUser($id)
    {
        User::find($id)->delete();
        Address::where('user_id',$id)->delete();
        return response()->json(['success'=>'User Deleted Successfully!']);
    }

}
