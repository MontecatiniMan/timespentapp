<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return Task[]|Collection
     */
    public function index(Request $request)
    {
        return Task::withTrashed()->where([Task::ATTR_USER_ID => $request->user()->id])->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Task|Response
     */
    public function store(Request $request)
    {
        $task = $request->task;

        $newTask = new Task;
        $newTask->user_id = $request->user()->id;
        $newTask->date = Carbon::parse($task['date']);
        $newTask->title = $task['title'];
        $newTask->hours = $task['hours'];
        $newTask->deleted_at = null;
        $newTask->save();

        return $newTask;
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function show(Request $request)
    {
        $task = json_decode($request->task);
        $result = Task::where([Task::ATTR_ID => $task->id]);

        if (null === $result) {
            return new Response('Task not found', 404);
        }

        return $result;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Task    $task
     *
     * @return array
     */
    public function update(Request $request, Task $task)
    {
        $taskData = json_decode($request->taskData, true);
        $taskData['date'] = Carbon::parse($taskData['date']);

        $result = Task::withTrashed()->find($task->id);
        $result->restore();
        $result->update($taskData);
        $result->save();

        return ['Task' => $result];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     *
     * @param Task    $task
     *
     * @return Response
     * @throws Exception
     */
    public function destroy(Request $request)
    {
        $result = Task::withTrashed()->find($request->task);

        if (null === $result) {
            return new Response('Task not found', 404);
        }

        if (null !== $result->deleted_at) {
            $result->restore();

            return new Response('Task ' . $result->id . ' was restored', 200);
        }

        $result->delete();

        return new Response('Task ' . $result->id . ' was deleted', 200);
    }
}
