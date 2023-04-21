<?php

namespace App\Repositories\Branch;

use App\Models\Branch\Branch;
use App\Models\Master\Cluster;
use App\Models\Master\Manager;
use App\Models\Scheme;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use App\Repositories\interfaces\Branch\SchemeInterface;
use Carbon\Carbon;
use DateTime;

class SchemeRepository extends BaseRepository implements SchemeInterface
{
    public function getModel()
    {
        return Scheme::class;
    }
    public function listSchemes()
    {
        return Scheme::latest()->get();
    }
    public function listBranchSchemes($id)
    {
        $manager = Manager::where('user_id', $id)->first();
        return Scheme::where('branch_id', $manager->branch_id)->latest()->get();
    }
    public function getScheme(Int $id)
    {
        return Scheme::find($id);
    }
    public function createScheme(Request $args)
    {
        return DB::transaction(function () use ($args) {
            $scheme = Scheme::create([
                'name' => $args['name'],
                'scheme_a_id' => $args['scheme_a_id'],
                'scheme_n_id' => $args['scheme_n_id'],
                // 'branch_id' => $args['branch_id'],
                'details' => $args['details'],
                'end_date' => $args['end_date'],
                'start_date' => $args['start_date'],
                'scheme_collection_day' => $args['collection_day'],
                'join_start_date' => $args['join_start_date'],
                'join_end_date' => $args['join_end_date'],
                'advance' => '200',
                'cluster_id' => $args['cluster_id'],
                'total_amount' => (30 * 200), // 30 weeks per 200
                'status' => $args['status'] == 'on' ? true : false,
            ]);
            $cluster = Cluster::with('branches')->find($args->cluster_id);
            $branches = $cluster->branches;
            foreach ($branches as $key => $value) {
                Branch::where('id', $value->id)->update([
                    'scheme_id' => $scheme->id,
                ]);
            }
            if (isset($args->scheme_image)) {
                $scheme->addMediaFromBase64(json_decode($args->scheme_image)->data)
                    ->usingFileName(Str::random() . '.jpeg')
                    ->toMediaCollection('scheme_images');
            }
            return $scheme;
        });
    }
    public function updateScheme(Request $args, $id)
    {

        return DB::transaction(function () use ($args, $id) {
            $schemeId = Scheme::find($id);
            $scheme = Scheme::where('id', $id)
                ->update([
                    'name' => $args['name'],
                    'scheme_a_id' => $args['scheme_a_id'],
                    'scheme_n_id' => $args['scheme_n_id'],
                    'branch_id' => $args['branch_id'],
                    'cluster_id' => $args['cluster_id'],
                    'details' => $args['details'],
                    'end_date' => $args['end_date'],
                    'start_date' => $args['start_date'],
                    'scheme_collection_day' => $args['collection_day'],
                    'join_start_date' => $args['join_start_date'],
                    'join_end_date' => $args['join_end_date'],
                    'advance' => '200',
                    'total_amount' => (30 * 200), // 30 weeks per 200
                    'status' => $args['status'] == 'on' ? true : false,
                ]);
            if (isset($args->scheme_image)) {
                $schemeId->clearMediaCollection('scheme_images');
                $schemeId->addMediaFromBase64(json_decode($args->scheme_image)->data)
                    ->usingFileName(Str::random() . '.jpeg')
                    ->toMediaCollection('scheme_images');
            }
            return $scheme;
        });
    }
    public function deleteScheme(Int $id)
    {
    }
}
