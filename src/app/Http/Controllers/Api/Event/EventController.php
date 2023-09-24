<?php

namespace App\Http\Controllers\Api\Event;

use App\Filters\QueryFilter;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Requests\Api\Event\CreateEventRequest;
use App\Models\Event;
use App\Services\Dto\EventDto\EventDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends ApiBaseController
{
    public function create(CreateEventRequest $request)
    {
        DB::beginTransaction();

        try {
            $event = Event::create(new EventDto($request->validationData()));
        } catch (\Throwable $exception) {
            DB::rollBack();
            return $this->response(error: $exception->getMessage(), status: 500);
        }

        DB::commit();

        return $this->response($event);
    }

    /**
     * Так как по задаче нет понимание относительно пагинации то выводим весь список событий
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $queryEvents = Event::query();
        // Если в запросе есть фильтры - применим их
        $queryEvents = (new QueryFilter($request))->apply($queryEvents);

        return $this->response($queryEvents->get());
    }

    public function show(Event $event)
    {
        // Вернем событие с участниками
        return $this->response($event->load('participants'));
    }

    /**
     * Пользователь от которого делается запрос присоединяется к выбранному событию
     * @param Event $event
     * @return JsonResponse
     */
    public function join(Event $event)
    {
        DB::beginTransaction();

        try {
            Auth::user()->participatingEvents()->attach($event);
        } catch (\Throwable $exception) {
            DB::rollBack();
            return $this->response(error: $exception, status: 500);
        }
        DB::commit();

        return $this->response();
    }

    public function leave(Event $event)
    {
        // Проверка, участвует ли пользователь в событии
        if (!$event->participants->contains(Auth::id())) {
            return $this->response(error: 'Вы не участвуете в данном событии', status: 403);
        }

        DB::beginTransaction();

        try {
            Auth::user()->participatingEvents()->detach($event);
        } catch (\Throwable $exception) {
            DB::rollBack();
            return $this->response(error: $exception, status: 500);
        }
        DB::commit();

        return $this->response();
    }

    public function destroy(Event $event)
    {
        if (Auth::id() !== $event->creator_id) {
            return $this->response(error: 'Пользователю доступно удаление только своего события', status: 403);
        }

        DB::beginTransaction();

        try {
            $event->delete();
        } catch (\Throwable $exception) {
            DB::rollBack();
            return $this->response(error: $exception->getMessage(), status: 500);
        }
        DB::commit();

        return $this->response();
    }

}
