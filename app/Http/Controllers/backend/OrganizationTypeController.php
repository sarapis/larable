<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\OrganizationType;
use App\Layout;
use DB;
use Illuminate\Http\Request;
use Sentinel;

class OrganizationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizationTypes = OrganizationType::get();
        $layout = Layout::find(1);
        return view('backEnd.organizationType.index', compact('organizationTypes', 'layout'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('backEnd.organizationType.create');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function change_activate(Request $request) 
    {
        $layout = Layout::find(1);
        if ($request->on == 'true') {
            $layout->activate_organization_types = 0;
        } else {
            $layout->activate_organization_types = 1;
        }
        $layout->save();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'organization_type' => 'required',
        ]);
        try {
            DB::beginTransaction();
            OrganizationType::create([
                'organization_type' => $request->get('organization_type'),
                'notes' => $request->get('notes'),
                'created_by' => Sentinel::check()->id,
            ]);
            DB::commit();
            return redirect()->to('organizationTypes')->with('success', 'organization type created successfully');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->to('organizationTypes')->with('error', $th->getMessage());

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $organizationType = OrganizationType::whereId($id)->first();

        return view('backEnd.organizationType.edit', compact('organizationType'));

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
        $this->validate($request, [
            'organization_type' => 'required',
        ]);
        try {
            DB::beginTransaction();
            OrganizationType::whereId($id)->update([
                'organization_type' => $request->get('organization_type'),
                'notes' => $request->get('notes'),
                'created_by' => Sentinel::check()->id,
            ]);
            DB::commit();
            return redirect()->to('organizationTypes')->with('success', 'organization type updated successfully');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->to('organizationTypes')->with('error', $th->getMessage());

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            OrganizationType::whereId($id)->delete();
            DB::commit();
            return redirect()->to('organizationTypes')->with('success', 'organization type deleted successfully');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->to('organizationTypes')->with('error', $th->getMessage());

        }

    }
}
