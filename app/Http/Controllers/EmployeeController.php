<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $pageTitle = 'Employee List';
    //     // RAW SQL QUERY
    //     $employees = DB::select('
    //         select *, employees.id as employee_id, positions.name as position_name
    //         from employees
    //         left join positions on employees.position_id = positions.id
    //     ');
    //     // RAW SQL builder
    //     $employees = DB::table('employees')
    //                 ->select('*', 'employees.id as employee_id', 'positions.name as position_name')
    //                 ->leftJoin('positions','employees.position_id', '=', 'positions.id')
    //                 ->get();
    //     return view('employee.index', [
    //         'pageTitle' => $pageTitle,
    //         'employees' => $employees
    //     ]);
    public function index()
    {
        $pageTitle = 'Employee List';
    
        // ELOQUENT
        $employees = Employee::all();
    
        return view('employee.index', [
            'pageTitle' => $pageTitle,
            'employees' => $employees
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // // RAW SQL builder
        // $pageTitle ='Create Employee';
        // $Posision = DB::select('select * from positions');
        // $positions = DB::table('positions')
        //             ->select('*')
        //             ->get();
        // return view('employee.create',compact('pageTitle','positions'));
        $pageTitle = 'Create Employee';

        // ELOQUENT
        $positions = Position::all();
    
        return view('employee.create', compact('pageTitle', 'positions'));
    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // // RAW SQL builder
        // $messages = [
        //     'required' => ':Attribute harus diisi.',
        //     'email' => 'Isi :attribute dengan format yang benar',
        //     'numeric' => 'Isi :attribute dengan angka'
        // ];
    
        // $validator = Validator::make($request->all(), [
        //     'firstName' => 'required',
        //     'lastName' => 'required',
        //     'email' => 'required|email',
        //     'age' => 'required|numeric',
        // ], $messages);
    
        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        // DB::table('employees')->insert([
        //     'firstname' => $request->firstName,
        //     'lastname' => $request->lastName,
        //     'email' => $request->email,
        //     'age' => $request->age,
        //     'position_id' => $request->position
        // ]);
        // return redirect()->route('employees.index');

        // $messages = [
        //     'required' => ':Attribute harus diisi.',
        //     'email' => 'Isi :attribute dengan format yang benar',
        //     'numeric' => 'Isi :attribute dengan angka'
        // ];
    
        // $validator = Validator::make($request->all(), [
        //     'firstName' => 'required',
        //     'lastName' => 'required',
        //     'email' => 'required|email',
        //     'age' => 'required|numeric',
        // ], $messages);
    
        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }
    
        // // ELOQUENT
        // $employee = New Employee;
        // $employee->firstname = $request->firstName;
        // $employee->lastname = $request->lastName;
        // $employee->email = $request->email;
        // $employee->age = $request->age;
        // $employee->position_id = $request->position;
        // $employee->save();
    
        // return redirect()->route('employees.index');
        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar',
            'numeric' => 'Isi :attribute dengan angka'
        ];
    
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
        ], $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Get File
        $file = $request->file('cv');
    
        if ($file != null) {
            $originalFilename = $file->getClientOriginalName();
            $encryptedFilename = $file->hashName();
    
            // Store File
            $file->store('public/files');
        }
    
        // ELOQUENT
        $employee = New Employee;
        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->position_id = $request->position;
    
        if ($file != null) {
            $employee->original_filename = $originalFilename;
            $employee->encrypted_filename = $encryptedFilename;
        }
    
        $employee->save();
    
        return redirect()->route('employees.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // // RAW SQL builder
        // $pageTitle = 'Employee Detail';
        // $employees = collect(DB::select(
        //     'select *, employees.id as employee_id, positions.name as position_name
        //     from employees
        //     left join positions on employees.position_id = positions.id where employees.id = ?', [$id]
        // ))->first();
        // $employee = DB::table('employees')
        //             ->select('*', 'employees.id as employee_id', 'positions.name as position_name')
        //             ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
        //             ->where('employees.id', '=', $id)
        //             ->first();
        // return view('employee.show', compact('pageTitle', 'employee'));
        $pageTitle = 'Employee Detail';

        // ELOQUENT
        $employee = Employee::find($id);

        return view('employee.show', compact('pageTitle', 'employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // // RAW SQL builder
        // $pageTitle = 'Employee Edit';
        // $employee = DB::table('employees')
        //             ->select('*', 'employees.id as employee_id', 'positions.name as position_name')
        //             ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
        //             ->where('employees.id', '=', $id)
        //             ->first();
        // $positions = DB::table('positions')
        //             ->select('*')
        //             ->get();
        // return view('employee.edit', compact('pageTitle', 'employee','positions'));

        $pageTitle = 'Edit Employee';

        // ELOQUENT
        $positions = Position::all();
        $employee = Employee::find($id);

        return view('employee.edit', compact('pageTitle', 'positions', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // // RAW SQL builder
        // $messages = [
        //     'required' => ':Attribute harus diisi.',
        //     'email' => 'Isi :attribute dengan format yang benar',
        //     'numeric' => 'Isi :attribute dengan angka'
        // ];

        // $validator = Validator::make($request-> all(), [
        //     'firstName' => 'required',
        //     'lastName' => 'required',
        //     'email' => 'required|email',
        //     'age' => 'required|numeric',
        // ], $messages);

        // if ($validator->fails()) {
        //     return redirect()->back()-> withErrors($validator)->withInput();
        // }
        // DB::table('employees')-> where('id',$id)-> update([
        //     'firstname' => $request-> firstName,
        //     'lastname' => $request-> lastName,
        //     'email' => $request-> email,
        //     'age' => $request-> age,
        //     'position_id' => $request->position,
        // ]);
        // return redirect()->route('employees.index');

        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar',
            'numeric' => 'Isi :attribute dengan angka'
        ];
    
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
        ], $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // ELOQUENT
        $employee = Employee::find($id);
        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->position_id = $request->position;
        $employee->save();
    
        return redirect()->route('employees.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    //     // RAW SQL builder
    //     DB::table('employees')
    //     -> where('id', $id)
    //     -> delete();

    // return redirect()->route('employees.index');

    // ELOQUENT
    Employee::find($id);
     $employee = Employee::findOrFail($id);

     // Hapus file CV jika ada
     if ($employee->encrypted_filename && Storage::disk('public')->exists('files/' . $employee->encrypted_filename)) {
            Storage::disk('public')->delete('files/' . $employee->encrypted_filename);
            $employee->original_filename = null;
            $employee->encrypted_filename = null;
    }

     // Hapus data karyawan
     $employee->delete();
     return redirect()->route('employees.index');
    }

    public function downloadFile($employeeId)
    {
        $employee = Employee::find($employeeId);
        $encryptedFilename = 'public/files/'.$employee->encrypted_filename;
        $downloadFilename = Str::lower($employee->firstname.'_'.$employee->lastname.'_cv.pdf');

        if(Storage::exists($encryptedFilename)) {
            return Storage::download($encryptedFilename, $downloadFilename);
        }
    }
}
