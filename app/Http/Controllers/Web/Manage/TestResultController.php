<?php

namespace App\Http\Controllers\Web\Manage;

use App\Http\Controllers\Controller;
use App\Http\Transformers\TestResultTransformer;
use App\Models\Test;

class TestResultController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/manage/tests/{test_id}/results",
     *  tags={"web/test"},
     *  summary="考试记录列表",
     *  description="",
     *  produces={"application/json"},
     *  @SWG\Parameter(ref="#/parameters/TestResultQuery-task_id"),
     *  @SWG\Parameter(ref="#/parameters/TestResultQuery-task:title"),
     *  @SWG\Parameter(ref="#/parameters/TestResultQuery-test_id"),
     *  @SWG\Parameter(ref="#/parameters/TestResultQuery-test:title"),
     *  @SWG\Parameter(ref="#/parameters/TestResultQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/TestResultQuery-user:username"),
     *  @SWG\Parameter(ref="#/parameters/TestResultQuery-right_count"),
     *  @SWG\Parameter(ref="#/parameters/TestResultQuery-questions_count"),
     *  @SWG\Parameter(ref="#/parameters/TestResultQuery-score"),
     *  @SWG\Parameter(ref="#/parameters/TestResultQuery-is_finished"),
     *  @SWG\Parameter(ref="#/parameters/TestResultQuery-created_at"),
     *  @SWG\Parameter(ref="#/parameters/TestResult-sort"),
     *  @SWG\Parameter(ref="#/parameters/TestResult-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/TestResultPagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Test $test)
    {
        $this->authorize('isCourseTeacher', $test->course);

        $paginator = $test->results()->filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($paginator, new TestResultTransformer());
    }
}
