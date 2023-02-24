<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function listAll(){
        $data=Student::orderBy("id","desc")
            ->paginate(20);
        return view("admin.student.list",[
            "data"=>$data
        ]);
    }
    public function createStudent(){
        return view("admin.student.create");
    }
    public function store(Request $request){
        $request->validate([
            "name"=>"required|string|min:6",
            "age"=>"required|numeric|min:18",
            "address"=>"required|string|min:3",
            "telephone"=>"required|string|min:9",

        ],[
            "required"=>"Vui lòng nhập thông tin",
            "string"=> "Phải nhập vào là một chuỗi văn bản",
            "min"=> "Phải nhập :attribute  tối thiểu :min",
            "mimes"=>"Vui lòng nhập đúng định dạng ảnh"
        ]);
        try{
            $student = Student::create([
                "name"=>$request->get("name"),
                "age"=>$request->get("age"),
                "address"=>$request->get("address"),
                "telephone"=>$request->get("telephone"),
            ]);
            return redirect()->to("admin/student");
        }catch (\Exception $e){
            return redirect()->back();
        }


    }
}
