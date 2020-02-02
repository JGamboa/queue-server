<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateJobFormRequest;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $jobs = Auth::user()->jobs()->orderBy('id', 'desc');
        $currentPage = $request->input('page');

        Paginator::currentPageResolver( function() use ($currentPage) {
            return $currentPage;
        });


        if ($request->filled('itemsPerPage')) {
            if($request->input('itemsPerPage') == -1){
                $jobs = $jobs->get();
            }else{
                $jobs = $jobs->paginate($request->input('itemsPerPage'));
            }
        } else {
            $jobs = $jobs->paginate(10);
        }

        return response()->json($jobs->toArray(), 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateJobFormRequest $request)
    {
        $job = Job::create($request->input());

        return response()->json($job, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param Job $job
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Job $job)
    {
        if($job->submitter_id !== Auth::user()->id){
            return response()->json($job, 200);
        }else{
            return response()->json(['message'=>'You can only see the information of the jobs that you own'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
