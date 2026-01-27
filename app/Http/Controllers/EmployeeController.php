<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\KategoriEmployee;

class EmployeeController extends Controller
{
    //
    // public function index(Request $request)
    // {
    //     $query = Employee::with(['user.role','kategori']);
    //     $users = User::all();
    //     $catogories = KategoriEmployee::all();

    //     if ($request->filled('search')) {
    //         $query->where(function ($q) use ($request) {
    //             $q->where('name', 'like', '%' . $request->search . '%')
    //             ->orWhere('nip', 'like', '%' . $request->search . '%');
    //         });
    //     }

    //     if ($request->filled('role_id')) {
    //         $query->whereHas('user', function ($q) use ($request) {
    //             $q->where('role_id', $request->role_id);
    //         });
    //     }

    //     $employees = Employee::with('user.role')
    //         ->orderBy('name')
    //         ->paginate(10)
    //         ->withQueryString();
    //     $roles = Role::orderBy('name')->get();

    //     return view('employees.index', compact('employees', 'roles','users','catogories'));
    // }


    public function index(Request $request)
    {
        $users = User::all();
        $catogories = KategoriEmployee::all();
        $roles = Role::orderBy('name')->get();

        // QUERY UTAMA
        $query = Employee::with(['user.role', 'kategori']);

        // 🔍 SEARCH
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('nip', 'like', '%' . $request->search . '%');
            });
        }

        // 🎯 FILTER ROLE
        if ($request->filled('role_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('role_id', $request->role_id);
            });
        }

        // 📄 PAGINATION (PAKAI QUERY YANG SAMA)
        $employees = $query
            ->orderBy('position_id','asc')
            ->paginate(10)
            ->withQueryString();

        return view('employees.index', compact(
            'employees',
            'roles',
            'users',
            'catogories'
        ));
    }


    public function store(Request $request)
    {
        $request->validate(
            [
                'user_id' => 'nullable|unique:employees,user_id',
                'nip' => 'required|unique:employees,nip',
                'name' => 'required',
                'position_id' => 'required',
                'department' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'kategori_id' => 'nullable|exists:kategori_employees,id',
            ],
                [
                    'user_id.unique' => 'User ini sudah terhubung dengan pegawai lain.',
                    'nip.required' => 'NIP wajib diisi.',
                    'nip.unique' => 'NIP sudah terdaftar.',
                    'name.required' => 'Nama pegawai wajib diisi.',
                    'position_id.required' => 'Posisi wajib diisi.',
                    'department.required' => 'Departemen wajib diisi.',
                    'email.required' => 'Email wajib diisi.',
                    'email.email' => 'Format email tidak valid.',
                    'phone.required' => 'Nomor telepon wajib diisi.',
                    'kategori_id.exists' => 'Kategori tidak valid.',
                ]
            );

            Employee::create([
                'user_id' => $request->user_id,
                'kategori_id' => $request->kategori_id,
                'nip' => $request->nip,
                'name' => $request->name,
                'position_id' => $request->position_id,
                'department' => $request->department,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

        return back()->with('success', 'Pegawai berhasil ditambahkan');
    }


    


    public function update(Request $request, $id)
    {
        try {
            $employee = Employee::findOrFail($id);

            $request->validate([
                'user_id' => 'nullable|unique:employees,user_id,' . $employee->id,
                'nip' => 'required|unique:employees,nip,' . $employee->id,
                'name' => 'required',
                'position_id' => 'required',
                'department' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'kategori_id' => 'nullable|exists:kategori_employees,id',
            ]);

            $employee->update([
                'user_id' => $request->user_id,
                'kategori_id' => $request->kategori_id,
                'nip' => $request->nip,
                'name' => $request->name,
                'position_id' => $request->position_id,
                'department' => $request->department,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            return back()->with('success', 'Data pegawai berhasil diperbarui');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }


    public function destroy($id)
    {
        Employee::findOrFail($id)->delete();
        return back()->with('success','Pegawai dihapus');
    }
}
