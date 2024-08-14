<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

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

  //zoznam  
  public function usersList(Request $request)
  { 
    return response()->json($this->users);
  }

  //filtrovanie
  public function searchUsers(Request $request)
  {
    // Získajte všetky filtre z dotazu
    $filters = $request->only(['id', 'name', 'age', 'gender']);  
    // Filtrovanie používateľov podľa zadaných parametrov
    $result = [];
    foreach ($this->users as $key => $user) {
      foreach ($filters as $key => $value) {
        if (!empty($value)) {      
          if ($key === 'name') {
            // Pre meno sa robí vyhľadávanie podľa podreťazca (case-insensitive)            
            if (stripos($user[$key], $value) !== false) {          
              $result[$user['id']] = $user;
            }
          } else if (strtolower($user[$key]) == strtolower($value)) {
            // Porovnanie pre iné parametre (id, age, gender)
            $result[$user['id']] = $user;
          }
        }
      }      
    }

    // Vráti upravený zoznam používateľov
    return response()->json($result); 
  }

  //vymazanie podla id user
  public function deleteUser(Request $request, $id)
  {
    $users = $this->users;
    $result = [];
 
    foreach ($this->users as $key => $user) {
       if($user['id'] != $id){
        $result[$key] = $user;
       }
    }
    return response()->json($users);
  }


  //editacia usera
  public function updateUser(Request $request, $id)
  {
    $users = $this->users;

    foreach ($users as $key => $user) {
      if($user['id'] = $id ){
        $users[$key]['name'] = $request->name;
        $users[$key]['age'] = $request->age;
        $users[$key]['gender'] = $request->gender;
        return response()->json($users);
      }    
    }
    return response()->json(['message' => 'Úprava používateľa nebola úspešná.']);
  }  
}
