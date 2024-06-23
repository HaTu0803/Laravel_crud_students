<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\Interfaces\StudentServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function __construct(StudentServiceInterface $studentService)
    {
        $this->studentService = $studentService;
    }

    // Get all student
    public function index()
    {
        $students = $this->studentService->getAllStudent();

        return response()->json([
            'status' => 200,
            'data' => $students
        ], 200);

        //        if ($students->count() > 0) {
        //            return response()->json([
        //                'status:' => 200,
        //                'message:' => $students
        //            ], 200);
        //        } else {
        //            return response()->json([
        //                'status:' => 404,
        //                'message:' => 'No record found'
        //            ], 404);
        //        }
    }

    // Create new student
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|digits:10',
            'email'=> 'required|email|max:255|unique:students,email',
            // 'status'=> 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status:' => 422,
                'errors:' => $validator->messages()
            ], 422);
        } else {
            // $student = $this->studentService->create([
            //     'name' => $request->name,
            //     'phone' => $request->phone,
            //     'email' => $request->email,
            //     'status' => 'Active', // Đặt giá trị mặc định cho trường status là 'Active'
            // ]);

            $student = $this->studentService->create($request);

            if ($student) {
                return response()->json([
                    'status:' => 200,
                    'message:' => 'Student created successfully'
                ], 200);
            } else {
                return response()->json([
                    'status:' => 500,
                    'message:' => 'Something went wrong!'
                ], 500);
            }
        }
    }

    // Get student by ID
    public function getById($id)
    {
        //        $student = Student::find($id);
        $student = $this->studentService->getStudentById($id);

        if ($student) {
            return response()->json([
                'status:' => 200,
                'student:' => $student
            ], 200);
        } else {
            return response()->json([
                'status:' => 404,
                'message:' => 'No student found with ID: ' . $id,
            ], 404);
        }
    }

    // Update student by ID
    public function updateById(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|digits:10',
            'email'=> 'required|email|max:255|unique:students,email',
            'status'=> 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status:' => 422,
                'errors:' => $validator->messages()
            ], 422);
        } else {
            //            $student = Student::find($id);
            $student = $this->studentService->update($request, $id);
            if ($student) {
                $student->update([
                    'name' => $request->name,
                    'phone'=> $request->phone,
                    'email'=> $request->email,
                    'status'=> $request->status,    
                ]);
                return response()->json([
                    'status:' => 200,
                    'message:' => 'Student updated successfully'
                ], 200);
            } else {
                return response()->json([
                    'status:' => 404,
                    'message:' => 'No student found with ID: ' . $id,
                ], 404);
            }
        }
    }

    // Delete student by ID
    public function deleteById($id)
    {
        //        $student = Student::find($id);
        $student = $this->studentService->delete($id);
        if ($student) {
            return response()->json([
                'status:' => 200,
                'student:' => 'Student deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'status:' => 404,
                'message:' => 'No student found with ID: ' . $id,
            ], 404);
        }
    }
}
