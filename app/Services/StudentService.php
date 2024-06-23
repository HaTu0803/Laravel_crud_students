<?php

namespace App\Services;

use App\Models\Student;
use App\Services\Interfaces\StudentServiceInterface;
use Illuminate\Http\Request;

class StudentService implements StudentServiceInterface
{

    public function getAllStudent()
    {
        return Student::all();
    }

    public function getStudentById($id)
    {
        return Student::find($id);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|digits:10',
            'email'=> 'required|email|max:255|unique:students,email',
            // mặc định status lúc tạo là active
            // 'status'=> 'required|string|max:255',
           

        ]);
        $validated['status'] = 'Active';
        return Student::create($validated);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone'=> 'required|digits:10',
            'email'=> 'required|email|max:255|unique:students,email,'.$id,
            'status'=> 'required|string|max:255',
        ]);

        $student = Student::find($id);
        if ($student) {
            $student->update($validated);
            return $student;
        } else {
            return null;
        }
    }

    public function delete($id)
    {
        $student = Student::find($id);
        if ($student) {
            $student->delete();
            return true;
        } else {
            return false;
        }
    }
}
