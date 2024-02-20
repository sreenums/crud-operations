<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function saveuser(Request $request){

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

        return back()->withSuccess('User added successfully!');;
    }
}
