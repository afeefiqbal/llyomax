<?php

namespace App\Repositories\Warehouse;

use App\Models\User;
use App\Models\Warehouse\DeliveryBoy;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Warehouse\DeliveryBoyInterface;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DeliveryBoyRepository extends BaseRepository implements DeliveryBoyInterface
{
    public function getModel()
    {
        return DeliveryBoy::class;
    }
    public function listdeliveryExecutives(){
        return $this->model->all();

    }
    public function getDeliveryBoyById($id){
        return $this->model->find($id);
    }
    public function createDeliveryBoy($attributes){
        $user = new User();
        $usr = $user->create([
            'name' => $attributes->name,
            'email' => $attributes->email,
            'mobile' => $attributes->phone,
            'password' => Hash::make($attributes->password),
            'username' => $attributes->username,
        ]);
      $usr->assignRole('delivery-boy');
        // dd($user);
        $attributes['user_id'] = $usr->id;

        return $this->model->create($attributes->all());

    }
    public function updateDeliveryBoy($request,$id){
        $deliveryBoy = $this->model->find($id);
        $users = User::where('id',$deliveryBoy->user_id)->first();
        $users->name = $request->name;
        $users->email = $request->email;
        $users->mobile = $request->phone;
        $users->username = $request->username;
        $users->password = Hash::make($request->password);
         $users->save();
        $request->user_id = $users->id;
        $deliveryBoy->update($request->all());
        return $deliveryBoy;
    }
    public function deleteDeliveryBoy($id){
        $deliveryBoy = $this->model->find($id);
        $deliveryBoy->delete();
        return $deliveryBoy;

    }
}
