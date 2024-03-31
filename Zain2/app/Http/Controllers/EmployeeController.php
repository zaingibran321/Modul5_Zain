<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;
class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Employee List';
        return view('employee.index', ['pageTitle' => $pageTitle]);
        // RAW SQL QUERY
        $employees = DB::select('
        select *, employees.id as employee_id, positions.name as position_name
        from employees
        left join positions on employees.position_id = positions.id
');
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
        $pageTitle = 'Create Employee';
        $positions = DB::select('select * from positions');

        return view('employee.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        return $request->all();
        DB::table('employees')->insert([
            'firstname' => $request->firstName,
            'lastname' => $request->lastName,
            'email' => $request->email,
            'age' => $request->age,
            'position_id' => $request->position,
            ]);
            return redirect()->route('employees.index');
            }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    $pageTitle = 'Employee Detail';
    // RAW SQL QUERY
    $employee = collect(DB::select('
    select *, employees.id as employee_id, positions.name as
    position_name
    from employees
    left join positions on employees.position_id = positions.id
    where employees.id = ?
    ', [$id]))->first();
    return view('employee.show', compact('pageTitle', 'employee'));
    }

    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::table('employees')
->where('id', $id)
->delete();
return redirect()->route('employees.index');
}

    }
