<?php

namespace App\Http\Controllers\Backstage;

use App\Enums\Status;
use App\Http\Transformers\ClassroomTransformer;
use App\Models\Classroom;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * 首页
     *
     * @param Classroom $classroom
     * @return \Dingo\Api\Http\Response
     */
    public function index(Classroom $classroom, Request $request)
    {
        $classrooms = Classroom::filtered()->sorted()->paginate(self::perPage());

        // 统计信息
        $stat = [];
        $stat['total'] = $classroom->count();
        $stat['published'] = $classroom->published()->count();
        $stat['closed'] = $classroom->closed()->count();
        $stat['draft'] = $classroom->draft()->count();

        // 课程状态
        $status = ['draft' => '未发布', 'closed' => '已关闭', 'published' => '已发布'];

        return view('admin.classroom.index', compact('classrooms', 'stat', 'status'));
    }


    /**
     * @param Classroom $classroom
     * @return \Dingo\Api\Http\Response
     */
    public function recommendIndex(Classroom $classroom, Request $request)
    {
        $request->offsetSet('sort', 'recommended_seq,desc');

        $classrooms = Classroom::filtered()
            ->sorted()
            ->where('is_recommended', true)
            ->paginate(self::perPage());


        return view('admin.classroom.recommend_index', compact('classrooms'));

    }


    /**
     * 班级推荐页面
     *
     * @param Classroom $classroom
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function recommendShow(Classroom $classroom)
    {
        return view('admin.classroom.recommend', compact('classroom'));
    }


    /**
     * 发布
     *
     * @param Classroom $classroom
     * @return \Dingo\Api\Http\Response
     */
    public function publish(Classroom $classroom)
    {
        $classroom->status = request('status', 'published') === Status::PUBLISHED
            ? Status::PUBLISHED
            : Status::CLOSED;
        $classroom->save();

        return $this->response->item($classroom, new ClassroomTransformer());
    }


    /**
     * 推荐
     *
     * @param Classroom $classroom
     * @return \Dingo\Api\Http\Response
     */
    public function recommend(Classroom $classroom)
    {
        $classroom->is_recommended = (boolean)request('is_recommended', true);
        $classroom->recommended_seq = (integer)request('recommended_seq', 0);
        $classroom->recommended_at = $classroom->is_recommended ? now() : null;
        $classroom->save();

        return $this->response->item($classroom, new ClassroomTransformer());
    }


    /**
     *  删除
     *
     * @param Classroom $classroom
     * @return \Dingo\Api\Http\Response|void
     * @throws \Exception
     */
    public function destroy(Classroom $classroom)
    {
        if ($classroom->status == 'published') {
            return $this->response->errorForbidden('发布状态的班级无法删除!');
        }

        $classroom->delete();

        return $this->response->noContent();
    }
}
