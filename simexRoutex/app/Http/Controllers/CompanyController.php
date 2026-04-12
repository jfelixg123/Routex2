<?php

namespace App\Http\Controllers;

use App\Clases\Utilitat;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::with(['usuaris', 'industry'])->get();
        return CompanyResource::collection($companies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $company = new Company();
        $company->company_name = $request->company_name;
        $company->industria_id = $request->industria_id;

        try{
            $company->save();
            $response = (new CompanyResource($company))
            ->response()
            ->setStatusCode(201);
        }catch(QueryException $e){
            $missatge = Utilitat::errorMessage($e);
            $response = response()->json([
                'error' =>  $missatge
            ], 400);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        $company = Company::with(['usuaris', 'industry'])->find($company->id);
        return new CompanyResource($company);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $company = Company::find($id);
            if(!$company){
                $response = response()->json([
                    'error' => 'Compañia no trobada',
                ], 404);
            }else{
                $company->company_name = $request->company_name;
                $company->industria_id = $request->industria_id;

                try{
                    $company->save();
                    $response = (new CompanyResource($company))
                    ->response()
                    ->setStatusCode(201);
                }catch(QueryException $e){
                    $missatge = Utilitat::errorMessage($e);
                    $response = response()->json([
                        'error' =>  $missatge
                    ], 400);
                }
            }
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            $company = Company::find($id);
            if(!$company){
                $response = response()->json([
                    'error' => 'Compañia no trobada',
                ], 404);
            }else{
                $company->delete();
                $response = (new CompanyResource($company))
                ->response()
                ->setStatusCode(200);
            }
        }catch(QueryException $e){
            $missatge = Utilitat::errorMessage($e);
            $response = response()->json([
                'error' =>  $missatge
            ], 400);
        }

        return $response;
    }
}
