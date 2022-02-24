<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Company\ApiCompanyStoreRequest;
use App\Http\Requests\Api\Company\ApiCompanyUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Storage;
use Auth;
use Arr;

class CompanyController extends ApiController
{
    /**
    *
    * @OA\Get(
    *   path="/api/v1/companies",
    *   tags={"Companies"},
    *   summary="Get companies",
    *   description="Returns all companies",
    *   operationId="indexCompany",
    *   security={
    *       {"bearerAuth": {}}
    *   },
    *   @OA\Parameter(
    *       name="page",
    *       in="query",
    *       description="Number of page",
    *       required=false,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Show all companies.",
    *       @OA\MediaType(
    *           mediaType="application/json"
    *       )
    *   ),
    *   @OA\Response(
    *       response=401,
    *       description="Not authenticated."
    *   ),
    *   @OA\Response(
    *       response=403,
    *       description="Forbidden."
    *   )
    * )
    */
    public function index(Request $request) {
    	$companies=Company::with(['user'])->where('user_id', Auth::id())->get()->map(function($company) {
    		return $this->dataCompany($company);
    	});

    	$page=Paginator::resolveCurrentPage('page');
    	$pagination=new LengthAwarePaginator($companies, $total=count($companies), $perPage=15, $page, ['path' => Paginator::resolveCurrentPath(), 'pageName' => 'page']);
    	$pagination=Arr::collapse([$pagination->toArray(), ['code' => 200, 'status' => 'success']]);

    	return response()->json($pagination, 200);
    }

    /**
    *
    * @OA\Post(
    *   path="/api/v1/companies",
    *   tags={"Companies"},
    *   summary="Register company",
    *   description="Create a new company",
    *   operationId="storeCompany",
    *   security={
    *       {"bearerAuth": {}}
    *   },
    *   @OA\Parameter(
    *       name="name",
    *       in="query",
    *       description="Name of company",
    *       required=true,
    *       @OA\Schema(
    *           type="string"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="social_reason",
    *       in="query",
    *       description="Social reason of company",
    *       required=true,
    *       @OA\Schema(
    *           type="string"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="rfc",
    *       in="query",
    *       description="RFC of company",
    *       required=true,
    *       @OA\Schema(
    *           type="string"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="address",
    *       in="query",
    *       description="Address of company",
    *       required=true,
    *       @OA\Schema(
    *           type="string"
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Registered company.",
    *       @OA\MediaType(
    *           mediaType="application/json"
    *       )
    *   ),
    *   @OA\Response(
    *       response=401,
    *       description="Not authenticated."
    *   ),
    *   @OA\Response(
    *       response=403,
    *       description="Forbidden."
    *   ),
    *   @OA\Response(
    *       response=422,
    *       description="Data not valid."
    *   ),
    *   @OA\Response(
    *       response=500,
    *       description="An error occurred during the process."
    *   )
    * )
    */
    public function store(ApiCompanyStoreRequest $request) {
        $data=array('name' => request('name'), 'social_reason' => request('social_reason'), 'rfc' => request('rfc'), 'address' => request('address'), 'user_id' => Auth::id());
        $company=Company::create($data);

        if ($company) {
            Storage::disk('google')->makeDirectory($company->slug);

            $company=Company::with(['user'])->where('id', $company->id)->first();
            $company=$this->dataCompany($company);
            return response()->json(['code' => 201, 'status' => 'success', 'message' => 'La empresa ha sido registrada con éxito.', 'data' => $company], 201);
        }

        return response()->json(['code' => 500, 'status' => 'error', 'message' => 'Ocurrió un error durante el proceso, intente nuevamente.'], 500);
    }

    /**
    *
    * @OA\Get(
    *   path="/api/v1/companies/{id}",
    *   tags={"Companies"},
    *   summary="Get company",
    *   description="Returns a single company",
    *   operationId="showCompany",
    *   security={
    *       {"bearerAuth": {}}
    *   },
    *   @OA\Parameter(
    *       name="id",
    *       in="path",
    *       description="Search for ID",
    *       required=true,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Show company.",
    *       @OA\MediaType(
    *           mediaType="application/json"
    *       )
    *   ),
    *   @OA\Response(
    *       response=401,
    *       description="Not authenticated."
    *   ),
    *   @OA\Response(
    *       response=403,
    *       description="Forbidden."
    *   ),
    *   @OA\Response(
    *       response=404,
    *       description="No results found."
    *   )
    * )
    */
    public function show(Company $company) {
        if ($company->user_id!=Auth::id()) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Esta empresa no pertenece a este usuario.'], 403);
        }

    	$company=$this->dataCompany($company);
    	return response()->json(['code' => 200, 'status' => 'success', 'data' => $company], 200);
    }

    /**
    *
    * @OA\Put(
    *   path="/api/v1/companies/{id}",
    *   tags={"Companies"},
    *   summary="Update company",
    *   description="Update a single company",
    *   operationId="updateCompany",
    *   security={
    *       {"bearerAuth": {}}
    *   },
    *   @OA\Parameter(
    *       name="id",
    *       in="path",
    *       description="Search for ID",
    *       required=true,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="name",
    *       in="query",
    *       description="Name of company",
    *       required=true,
    *       @OA\Schema(
    *           type="string"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="social_reason",
    *       in="query",
    *       description="Social reason of company",
    *       required=true,
    *       @OA\Schema(
    *           type="string"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="rfc",
    *       in="query",
    *       description="RFC of company",
    *       required=true,
    *       @OA\Schema(
    *           type="string"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="address",
    *       in="query",
    *       description="Address of company",
    *       required=true,
    *       @OA\Schema(
    *           type="string"
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Update company.",
    *       @OA\MediaType(
    *           mediaType="application/json"
    *       )
    *   ),
    *   @OA\Response(
    *       response=401,
    *       description="Not authenticated."
    *   ),
    *   @OA\Response(
    *       response=403,
    *       description="Forbidden."
    *   ),
    *   @OA\Response(
    *       response=422,
    *       description="Data not valid."
    *   ),
    *   @OA\Response(
    *       response=500,
    *       description="An error occurred during the process."
    *   )
    * )
    */
    public function update(ApiCompanyUpdateRequest $request, Company $company) {
        if ($company->user_id!=Auth::id()) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Esta empresa no pertenece a este usuario.'], 403);
        }

        $data=array('name' => request('name'), 'social_reason' => request('social_reason'), 'rfc' => request('rfc'), 'address' => request('address'));
        $company->fill($data)->save();
        if ($company) {
            $company=Company::with(['user'])->where('id', $company->id)->first();
            $company=$this->dataCompany($company);
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'La empresa ha sido editada con éxito.', 'data' => $company], 200);
        }

        return response()->json(['code' => 500, 'status' => 'error', 'message' => 'Ocurrió un error durante el proceso, intente nuevamente.'], 500);
    }

    /**
    *
    * @OA\Delete(
    *   path="/api/v1/companies/{id}",
    *   tags={"Companies"},
    *   summary="Delete company",
    *   description="Delete a single company",
    *   operationId="destroyCompany",
    *   security={
    *       {"bearerAuth": {}}
    *   },
    *   @OA\Parameter(
    *       name="id",
    *       in="path",
    *       description="Search for ID",
    *       required=true,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Delete company.",
    *       @OA\MediaType(
    *           mediaType="application/json"
    *       )
    *   ),
    *   @OA\Response(
    *       response=401,
    *       description="Not authenticated."
    *   ),
    *   @OA\Response(
    *       response=403,
    *       description="Forbidden."
    *   ),
    *   @OA\Response(
    *       response=404,
    *       description="No results found."
    *   ),
    *   @OA\Response(
    *       response=500,
    *       description="An error occurred during the process."
    *   )
    * )
     */
    public function destroy(Company $company)
    {
        if ($company->user_id!=Auth::id()) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Esta empresa no pertenece a este usuario.'], 403);
        }

    	$company->delete();
    	if ($company) {
    		return response()->json(['code' => 200, 'status' => 'success', 'message' => 'La empresa ha sido eliminada con éxito.'], 200);
    	}

    	return response()->json(['code' => 500, 'status' => 'error', 'message' => 'Ocurrió un error durante el proceso, intente nuevamente.'], 500);
    }

    /**
    *
    * @OA\Put(
    *   path="/api/v1/companies/{id}/deactivate",
    *   tags={"Companies"},
    *   summary="Deactivate company",
    *   description="Deactivate a single company",
    *   operationId="deactivateCompany",
    *   security={
    *       {"bearerAuth": {}}
    *   },
    *   @OA\Parameter(
    *       name="id",
    *       in="path",
    *       description="Search for ID",
    *       required=true,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Deactivate company.",
    *       @OA\MediaType(
    *           mediaType="application/json"
    *       )
    *   ),
    *   @OA\Response(
    *       response=401,
    *       description="Not authenticated."
    *   ),
    *   @OA\Response(
    *       response=403,
    *       description="Forbidden."
    *   ),
    *   @OA\Response(
    *       response=404,
    *       description="No results found."
    *   ),
    *   @OA\Response(
    *       response=500,
    *       description="An error occurred during the process."
    *   )
    * )
     */
    public function deactivate(Request $request, Company $company) {
        if ($company->user_id!=Auth::id()) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Esta empresa no pertenece a este usuario.'], 403);
        }

        $company->fill(['state' => "0"])->save();
        if ($company) {
            $company=$this->dataCompany($company);
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'La empresa ha sido desactivada con éxito.', 'data' => $company], 200);
        }

        return response()->json(['code' => 500, 'status' => 'error', 'message' => 'Ocurrió un error durante el proceso, intente nuevamente.'], 500);
    }

    /**
    *
    * @OA\Put(
    *   path="/api/v1/companies/{id}/activate",
    *   tags={"Companies"},
    *   summary="Activate company",
    *   description="Activate a single company",
    *   operationId="activateCompany",
    *   security={
    *       {"bearerAuth": {}}
    *   },
    *   @OA\Parameter(
    *       name="id",
    *       in="path",
    *       description="Search for ID",
    *       required=true,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Activate company.",
    *       @OA\MediaType(
    *           mediaType="application/json"
    *       )
    *   ),
    *   @OA\Response(
    *       response=401,
    *       description="Not authenticated."
    *   ),
    *   @OA\Response(
    *       response=403,
    *       description="Forbidden."
    *   ),
    *   @OA\Response(
    *       response=404,
    *       description="No results found."
    *   ),
    *   @OA\Response(
    *       response=500,
    *       description="An error occurred during the process."
    *   )
    * )
     */
    public function activate(Request $request, Company $company) {
        if ($company->user_id!=Auth::id()) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Esta empresa no pertenece a este usuario.'], 403);
        }

        $company->fill(['state' => "1"])->save();
        if ($company) {
            $company=$this->dataCompany($company);
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'La empresa ha sido activada con éxito.', 'data' => $company], 200);
        }

        return response()->json(['code' => 500, 'status' => 'error', 'message' => 'Ocurrió un error durante el proceso, intente nuevamente.'], 500);
    }
}
