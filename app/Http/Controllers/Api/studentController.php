<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\MockObject\Builder\Stub;

use function Ramsey\Uuid\v1;

class studentController extends Controller
{
    public function index()
    {
        $students = Student::all();

        $data = [
            'students' => $students,
            'status' => 200
        ];

        return response()->json($data, 200);

    }

    public function store(Request $request)
    {
        // set a conditions of validator
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:student',
            'phone' => 'required|digits:10',
            'language' => 'required|in:English,Spanish,French'
        ]);

        // To check that the validator is ok
        if ($validator->fails()) {
            $data = [
                'message' => 'Error in data validation',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        // We try to create a student
        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'language' => $request->language
        ]);

        // If the student can't be created, we return an error
        if (!$student) {
            $data = [
                'message' => 'Error al crear el estudiante',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'student' => $student,
            'status' => 201
        ];
        return response()->json($data, 201);
    }

    public function show($id)
    {
        $student = Student::find($id);
        if (!$student) {
            $data = [
                'message' => 'Student not found',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'students' => $student,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            $data = [
                'message' => 'Student not found',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $student->delete();

        $data = [
            'message' => 'Deleted student',
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        // set a conditions of validator
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:student',
            'phone' => 'required|digits:10',
            'language' => 'required|in:English,Spanish,French'
        ]);

        // To check that the validator is ok
        if ($validator->fails()) {
            $data = [
                'message' => 'Error in data validation',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        // to validate exist student
        $student = Student::find($id);
        if (!$student) {
            $data = [
                'message' => 'Student not found',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        // We try to create a student
        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->language = $request->language;

        $student->save();

        $data = [
            'message' => 'Student updated',
            'student' => $student,
            'status' => 200
        ];

        return response()->json($data, 200);

    }

    public function updatePartial(Request $request, $id)
    {
        // set a conditions of validator
        $validator = Validator::make($request->all(), [
            'name' => 'max:255',
            'email' => 'email|unique:student',
            'phone' => 'digits:10',
            'language' => 'in:English,Spanish,French'
        ]);

        // To check that the validator is ok
        if ($validator->fails()) {
            $data = [
                'message' => 'Error in data validation',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        // to validate exist student
        $student = Student::find($id);
        if (!$student) {
            $data = [
                'message' => 'Student not found',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        // We try to create a student
        if ($request->has('name'))
            $student->name = $request->name;

        if ($request->has('email'))
            $student->email = $request->email;

        if ($request->has('phone'))
            $student->phone = $request->phone;

        if ($request->has('language'))
            $student->language = $request->language;

        $student->save();

        $data = [
            'message' => 'Student updated',
            'student' => $student,
            'status' => 200
        ];

        return response()->json($data, 200);

    }
}
