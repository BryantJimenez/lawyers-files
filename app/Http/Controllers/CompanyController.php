<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Http\Requests\Company\CompanyStoreRequest;
use App\Http\Requests\Company\CompanyUpdateRequest;
use Illuminate\Http\Request;
use Storage;
use Auth;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (Auth::user()->hasRole('Cliente')) {
            $companies=Company::where('user_id', Auth::id())->orderBy('id', 'DESC')->get();
        } else {
            $companies=Company::orderBy('id', 'DESC')->get();
        }
        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $customers=User::role('Cliente')->where('state', '1')->get();
        return view('admin.companies.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyStoreRequest $request) {
        if (Auth::user()->hasRole('Cliente')) {
            $user=Auth::user();
        } else {
            $user=User::where('slug', request('customer_id'))->first();
        }
        $data=array('name' => request('name'), 'social_reason' => request('social_reason'), 'address' => request('address'), 'user_id' => $user->id);
        $company=Company::create($data);

        if ($company) {
            Storage::disk('google')->makeDirectory($company->slug);
            return redirect()->route('companies.index')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Registro exitoso', 'msg' => 'La compañia ha sido registrada exitosamente.']);
        } else {
            return redirect()->route('companies.create')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Registro fallido', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.'])->withInputs();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company) {
        if (Auth::user()->hasRole('Cliente') && $company->user_id!=Auth::id()) {
            return redirect()->route('companies.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Acceso no permitido.']);
        }
        
        return view('admin.companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company) {
        if (Auth::user()->hasRole('Cliente') && $company->user_id!=Auth::id()) {
            return redirect()->route('companies.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Acceso no permitido.']);
        }

        $customers=User::role('Cliente')->where('state', '1')->get();
        return view('admin.companies.edit', compact("company", 'customers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyUpdateRequest $request, Company $company) {
        if (Auth::user()->hasRole('Cliente') && $company->user_id!=Auth::id()) {
            return redirect()->route('companies.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Acceso no permitido.']);
        }

        if (Auth::user()->hasRole('Cliente')) {
            $user=Auth::user();
        } else {
            $user=User::where('slug', request('customer_id'))->first();
        }
        $data=array('name' => request('name'), 'social_reason' => request('social_reason'), 'address' => request('address'), 'state' => request('state'), 'user_id' => $user->id);
        $company->fill($data)->save();        

        if ($company) {
            return redirect()->route('companies.edit', ['company' => $company->slug])->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Edición exitosa', 'msg' => 'La compañia ha sido editada exitosamente.']);
        } else {
            return redirect()->route('companies.edit', ['company' => $company->slug])->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        if (Auth::user()->hasRole('Cliente') && $company->user_id!=Auth::id()) {
            return redirect()->route('companies.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Acceso no permitido.']);
        }

        $company->delete();
        if ($company) {
            return redirect()->route('companies.index')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Eliminación exitosa', 'msg' => 'La compañia ha sido eliminada exitosamente.']);
        } else {
            return redirect()->route('companies.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Eliminación fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
        }
    }

    public function deactivate(Request $request, Company $company) {
        if (Auth::user()->hasRole('Cliente') && $company->user_id!=Auth::id()) {
            return redirect()->route('companies.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Acceso no permitido.']);
        }

        $company->fill(['state' => "0"])->save();
        if ($company) {
            return redirect()->route('companies.index')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Edición exitosa', 'msg' => 'La compañia ha sido desactivada exitosamente.']);
        } else {
            return redirect()->route('companies.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
        }
    }

    public function activate(Request $request, Company $company) {
        if (Auth::user()->hasRole('Cliente') && $company->user_id!=Auth::id()) {
            return redirect()->route('companies.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Acceso no permitido.']);
        }

        $company->fill(['state' => "1"])->save();
        if ($company) {
            return redirect()->route('companies.index')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Edición exitosa', 'msg' => 'La compañia ha sido activada exitosamente.']);
        } else {
            return redirect()->route('companies.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
        }
    }
}
