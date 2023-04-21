<?php

namespace App\Repositories\Master;

use App\Models\Branch\Branch;
use App\Models\Master\Cluster;
use App\Repositories\BaseRepository;
use App\Repositories\Master\CLusterInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CLusterRepository extends BaseRepository implements CLusterInterface
{
    public function getModel()
    {
        return \App\Models\Master\Cluster::class;
    }
    public function listClusters()
    {
        $clusters = Cluster::with('branches')->orderBy('id','desc')->get();
        return $clusters;
    }
    public function createCluster(Request $request)
    {
        $cluster = $this->model->create([
            'district_id' => $request->district_id,
            'cluster_id' => $request->cluster_id,
            'name' => $request->name,
        ]);
        foreach ($request->branches as $key => $value) {
            Branch::where('id',$value)->update([
                'cluster_id' => $cluster->id,
            ]);
            $branchCluster = DB::insert('insert into branch_cluster (branch_id,cluster_id) values (?,?)',[$value,$cluster->id]);
            # code...
        }
        return $cluster;
    }
    public function updateCluster($request, $id)
    {
        $clusters = $this->model->find($id);
        $cluster = $clusters->update([
            'district_id' => $request->district_id,
            'cluster_id' => $request->cluster_id,
            'name' => $request->name,
        ]);
        DB::delete('delete from branch_cluster where cluster_id = ?',[$id]);
        foreach ($request->branches as $key => $value) {
            Branch::where('cluster_id',$id)->update([
                'cluster_id' => null,
            ]);
            // Branch::where('id',$value)->update([
            //     'cluster_id' => $cluster->id,
            // ]);
            // DB::insert('insert into branch_cluster (branch_id,cluster_id) values (?,?)',[$value,Cluster::find($id)->id]);
        }
        foreach ($request->branches as $key => $value) {
            Branch::where('id',$value)->update([
                'cluster_id' => Cluster::find($id)->id,
            ]);
            DB::insert('insert into branch_cluster (branch_id,cluster_id) values (?,?)',[$value,Cluster::find($id)->id]);
        }
        return $cluster;
    }
    public function deleteCluster($id)
    {
        DB::delete('delete from branch_cluster where cluster_id = ?',[$id]);
        $cluster = $this->model->find($id)->delete();
        return $cluster;
    }
}
