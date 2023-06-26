<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use App\Models\User;
use App\Models\UserCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function all(Request $request)
    {
        $value = $request->get("filter");
        switch ($value) {
            case "down_difficulty":
                $courses = Course::orderBy("difficulty", "desc")->simplePaginate(10);
                break;
            case "up_difficulty":
                $courses = Course::orderBy("difficulty", "asc")->simplePaginate(10);
                break;
            case "down_duration":
                $courses = Course::orderBy("duration", "desc")->simplePaginate(10);
                break;
            case "up_duration":
                $courses = Course::orderBy("duration", "asc")->simplePaginate(10);
                break;
            default:
                $courses = Course::simplePaginate(10);
                break;
        }
        return view('course.all', ["courses" => $courses, "filter" => $value]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = (User::find(Auth::id())->isAdmin() ? Course::all() : Auth::user()->courses);
        return view('course.index', ["courses" => $courses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize("create", Course::class);
        return view('course.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        $this->authorize("create", Course::class);
        $form = $request->validated();
        $form["image"] = $request->file("image")->store("courses", "public");
        $form["user_id"] = Auth::id();
        Course::create($form);
        return back()->with("success", "Course created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        if (!UserCourse::where("user_id", Auth::id())->where("course_id", $course->id)->count()) {
            UserCourse::create(["user_id" => Auth::id(), "course_id" => $course->id]);
        }
        $random_courses = Course::where("id", "!=", $course->id)->inRandomOrder()->limit(5)->get();
        $finished = UserCourse::where("user_id", Auth::id())->where("course_id", $course->id)->where("is_finished", true)->exists();
        return view('course.show', ["course" => $course, "random_courses" => $random_courses, "finished" => $finished]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $this->authorize("update", $course);
        return view('course.edit', ["course" => $course]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $this->authorize("update", $course);
        $form = $request->validated();
        if ($request->hasFile("image")) {
            if (file_exists("storage/" . $course->image)) {
                unlink("storage/" . $course->image);
            }
            $form["image"] = $request->file("image")->store("courses", "public");
        }
        $course->update($form);
        return back()->with("success", "Course updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $this->authorize("delete", $course);
        $course->delete();
        return back()->with("success", "Course deleted successfully");
    }

    public function end(Course $course)
    {
        // Check if the user already finished the course
        if (UserCourse::where("user_id", Auth::id())->where("course_id", $course->id)->where("is_finished", true)->count()) {
            return back()->with("error", "Course already finished!");
        }
        // Check if the user started the course
        elseif (!UserCourse::where("user_id", Auth::id())->where("course_id", $course->id)->count()) {
            return back()->with("error", "You can't finish a course you didn't start!");
        }
        UserCourse::where("user_id", Auth::id())->where("course_id", $course->id)->update(["is_finished" => true]);
        return back()->with("success", "Course finished!");
    }
}
