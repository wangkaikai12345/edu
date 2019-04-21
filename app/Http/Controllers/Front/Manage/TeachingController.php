<?php

namespace App\Http\Controllers\Front\Manage;

use App\Models\Classroom;
use App\Models\Course;
use App\Models\PlanTeacher;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeachingController extends Controller
{
    /**
     * 我的在教课程
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function teachCourse(Request $request)
    {
        $title = $request->title;
        $planTeachers = PlanTeacher::where(function ($query) {
            return $query->where('user_id', auth('web')->id())
                ->orWhere(function ($query) {
                    $cids = Course::where('user_id', auth('web')->id())->pluck('id');
                    return $query->orwhereIn('course_id', $cids);
                });
        })->when($title, function ($query) use ($title) {
            $cids = Course::where('title', 'like', '%' . $title . '%')->pluck('id');
            return $query->whereIn('course_id', $cids);
        })
            ->groupBy('plan_id')
            ->with(['plan', 'course'])
            ->paginate(config('theme.my_course_num'));

        return view('teacher.teaching', compact('planTeachers'));
    }

    /**
     * 我的在教班级
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author 王凯
     */
    public function teachClass()
    {
        $classrooms = Classroom::where('user_id', auth('web')->id())->paginate(config('theme.my_course_num'));

        return view('teacher.teaching_class', compact('classrooms'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
