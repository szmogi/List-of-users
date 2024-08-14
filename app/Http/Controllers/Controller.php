<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
  use AuthorizesRequests, ValidatesRequests;

  protected $users = [
    ['id' => 1, 'name' => 'John Doe', 'age' => 28, 'gender' => 'male'],
    ['id' => 2, 'name' => 'Jane Smith', 'age' => 22, 'gender' => 'female'],
    ['id' => 3, 'name' => 'Michael Brown', 'age' => 35, 'gender' => 'male'],
    ['id' => 4, 'name' => 'Emily Davis', 'age' => 30, 'gender' => 'female'],
    ['id' => 5, 'name' => 'William Wilson', 'age' => 41, 'gender' => 'male'],
    ['id' => 6, 'name' => 'Sophia Taylor', 'age' => 27, 'gender' => 'female'],
    ['id' => 7, 'name' => 'James Moore', 'age' => 29, 'gender' => 'male'],
    ['id' => 8, 'name' => 'Olivia Lee', 'age' => 25, 'gender' => 'female'],
    ['id' => 9, 'name' => 'Henry Martin', 'age' => 33, 'gender' => 'male'],
    ['id' => 10, 'name' => 'Charlotte Clark', 'age' => 26, 'gender' => 'female'],
  ];

  //users list  
  public function usersList(Request $request)
  {
    $users = Session::get('users', []);

    //if not set session
    if(empty($users)){
      Session::put('users', $this->users);
    } else {
      $this->users = $users;
    }
    
    return response()->json($this->users);
  }

  //filtered user
  public function searchUsers(Request $request)
  {
    // All filter values request
    $filters = $request->only(['id', 'name', 'age', 'gender']);
    // Filtering users according to the specified parameters
    $results = [];
    $users = Session::get('users', []);
    foreach ($users as $key => $user) {
      foreach ($filters as $key => $value) {
        if (!empty($value)) {      
          if ($key === 'name') {
            // Search by substring is done for the name          
            if (stripos($user[$key], $value) !== false) {
              $results[$user['id']] = $user;
            }
          } else if (strtolower($user[$key]) == strtolower($value)) {
            // Comparison for other parameters (id, age, gender)
            $results[$user['id']] = $user;
          }
        }
      }      
    }
    // Returns the edited list of users
    return response()->json($results); 
  }

  //destroy user 
  public function deleteUser(Request $request, $id)
  {
    $results = [];
    $users = Session::get('users', []);
 
    foreach($users as $key => $user) {
       if($user['id'] != $id){
        $results[$key] = $user;
       }
    }
    Session::put('users', $results);
    return response()->json($results);
  }

  //editing usera
  public function updateUser(Request $request, $id)
  {
    $users = Session::get('users', []);
    foreach ($users as $key => $user) {
      if($user['id'] = $id ){
        $users[$key]['name'] = $request->name;
        $users[$key]['age'] = $request->age;
        $users[$key]['gender'] = $request->gender;
        Session::put('users', $users);
        return response()->json($users);
      }    
    }
    return response()->json(['message' => 'Úprava používateľa nebola úspešná.']);
  }  
}
