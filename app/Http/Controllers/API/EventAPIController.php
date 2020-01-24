<?php

namespace App\Http\Controllers\API;

use App\Criteria\PaginateCriteria;
use App\Criteria\PastFutureCriteria;
use App\Criteria\WithoutDefaultEventsCriteria;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateEventAPIRequest;
use App\Http\Requests\API\DefaultAPIRequest;
use App\Http\Requests\API\UpdateEventAPIRequest;
use App\Models\Event;
use App\Repositories\EventRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use Response;

/**
 * Class EventController
 * @package App\Http\Controllers\API
 */
class EventAPIController extends AppBaseController
{
    
    /** @var  EventRepository */
    private $eventRepository;
    
    /**
     *
     * @SWG\Tag(
     *   name="Event",
     *   description="Operations with the Events"
     * ),
     * @SWG\Response(
     *          response="Events",
     *          description="Array of Event objects",
     *          ref="$/responses/200",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  type="object",
     *                  @SWG\Property(property="records", type="integer"),
     *                  @SWG\Property(property="perPage", type="integer"),
     *                  @SWG\Property(property="totalPages", type="integer"),
     *                  @SWG\Property(property="page", type="integer"),
     *                  @SWG\Property(
     *                       property="items",
     *                       type="array",
     *                       @SWG\Items(
     *                          allOf={
     *                              @SWG\Schema(ref="#/definitions/EventGet"),
     *                              @SWG\Schema(
     *                              @SWG\Property(
     *                                  property="medias",
     *                                  type="array",
     *                                  @SWG\Items(
     *                                      ref="#/definitions/MediaGet"
     *                                  ),
     *                              ),
     *                              ),
     *                          },
     *                      ),
     *                 )
     *             )
     *          )
     * ),
     * @SWG\Response(
     *          response="Event",
     *          ref="$/responses/200",
     *          description="Event object",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/EventGet"
     *              )
     *          )
     * ),
     */
    public function __construct(EventRepository $eventRepo)
    {
        
        $this->eventRepository = $eventRepo;
    }
    
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @SWG\Get(
     *      path="/events",
     *      summary="Get a listing of the Events.",
     *      tags={"Event"},
     *      @SWG\Parameter(ref="$/parameters/page"),
     *      @SWG\Parameter(
     *          in="query",
     *          name="restaurant_space_id",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          in="query",
     *          name="past",
     *          type="boolean",
     *          default="false",
     *      ),
     *      @SWG\Response(response=200, ref="#/responses/Events"),
     * )
     * @throws RepositoryException
     */
    
    public function index(DefaultAPIRequest $request)
    {
    
        $this->eventRepository->pushCriteria(new PastFutureCriteria($request));
        $this->eventRepository->pushCriteria(new WithoutDefaultEventsCriteria());
        $this->eventRepository->pushCriteria(new RequestCriteria($request));
        $this->eventRepository->pushCriteria(new PaginateCriteria($request));
    
        $events = $this->eventRepository->all();
        
        $data = $this->formDataArrayForResponse($events, $request, $this->eventRepository);
        
        return $this->sendResponse($data, 'Events retrieved successfully');
    }
    
    /**
     * @param CreateEventAPIRequest $request
     *
     * @return Response
     *
     *
     * @SWG\Post(
     *      path="/events",
     *      summary="Store a newly created Event in storage",
     *      tags={"Event"},
     *      consumes={"multipart/form-data"},
     *      @SWG\Parameter(
     *          name="restaurant_id",
     *          in="formData",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="title",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="description",
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="date_time",
     *          required=true,
     *          in="formData",
     *          type="string",
     *          format="date-time"
     *      ),
     *      @SWG\Parameter(
     *          name="ticket_price",
     *          required=true,
     *          in="formData",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="min_deposit",
     *          in="formData",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="image",
     *          in="formData",
     *          type="file"
     *      ),
     *      @SWG\Response(response=200, ref="#/responses/Event"),
     *      @SWG\Response(response="422", ref="#/responses/422"),
     * )
     * @throws ValidatorException
     */
    
    public function store(CreateEventAPIRequest $request)
    {
        
        $input = $request->all();
        
        $image = $request->file('image');
        if ( ! empty($image)) {
            $file_path        = $request->file('image')->store('img/events');
            $input[ 'image' ] = $image->hashName();
        }
        
        $event = $this->eventRepository->create($input);
        
        return $this->sendResponse($event->toArray(), 'Event saved successfully');
    }
    
    /**
     * @param int $id
     *
     * @return Response
     *
     * @SWG\Get(
     *      path="/events/{id}",
     *      summary="Display the specified Event",
     *      tags={"Event"},
     *      @SWG\Parameter(ref="$/parameters/id_in_path_required", description="id of Event"),
     *      @SWG\Response(response=200, ref="#/responses/Event"),
     *      @SWG\Response(response="404", description="Event not found"),
     * )
     */
    
    public function show($id)
    {
    
        $this->eventRepository->pushCriteria(new WithoutDefaultEventsCriteria());
        
        /** @var Event $event */
        $event = $this->eventRepository->find($id);
        
        if (empty($event)) {
            return $this->sendError('Event not found');
        }
        
        return $this->sendResponse($event->toArray(), 'Event retrieved successfully');
    }
    
    /**
     * @param int                   $id
     * @param UpdateEventAPIRequest $request
     *
     * @return Response
     *
     * @SWG\Post(
     *      path="/events/{id}",
     *      summary="Update the specified Event in storage",
     *      tags={"Event"},
     *      consumes={"multipart/form-data"},
     *      @SWG\Parameter(
     *          name="restaurant_id",
     *          in="formData",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="title",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="description",
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="date_time",
     *          required=true,
     *          in="formData",
     *          type="string",
     *          format="date-time"
     *      ),
     *      @SWG\Parameter(
     *          name="ticket_price",
     *          required=true,
     *          in="formData",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="min_deposit",
     *          in="formData",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="image",
     *          in="formData",
     *          type="file"
     *      ),
     *      @SWG\Parameter(ref="$/parameters/id_in_path_required", description="id of Event"),
     *      @SWG\Response(response=200, ref="#/responses/Event"),
     *      @SWG\Response(response="404", description="Event not found"),
     *      @SWG\Response(response="422", ref="#/responses/422"),
     * )
     * @throws ValidatorException
     */
    
    public function update($id, UpdateEventAPIRequest $request)
    {
        
        $input = $request->all();
    
        $this->eventRepository->pushCriteria(new WithoutDefaultEventsCriteria());
        /** @var Event $event */
        $event = $this->eventRepository->find($id);
        
        if (empty($event)) {
            return $this->sendError('Event not found');
        }
        
        $image = $request->file('image');
        
        if ( ! empty($image)) {
            $file_path        = $request->file('image')->store('img/events');
            $input[ 'image' ] = $image->hashName();
        }
        
        $event = $this->eventRepository->update($input, $id);
        
        return $this->sendResponse($event->toArray(), 'Event updated successfully');
    }
    
    /**
     * @param int $id
     *
     * @return Response
     *
     * @SWG\Delete(
     *      path="/events/{id}",
     *      summary="Remove the specified Event from storage",
     *      tags={"Event"},
     *      @SWG\Parameter(ref="$/parameters/id_in_path_required", description="id of Event"),
     *      @SWG\Response(response=200, ref="#/responses/200"),
     *      @SWG\Response(response="404", description="Event not found"),
     * )
     */
    
    public function destroy($id)
    {
    
        $this->eventRepository->pushCriteria(new WithoutDefaultEventsCriteria());
        /** @var Event $event */
        $event = $this->eventRepository->find($id);
        
        if (empty($event)) {
            return $this->sendError('Event not found');
        }
        
        $event->delete();
        
        return $this->sendResponse($id, 'Event deleted successfully');
    }
    
    /**
     * @return mixed
     *
     * @SWG\Get(
     *      path="/events/dropdown",
     *      summary="Get a listing of the Events for the dropdown without excluded ids",
     *      tags={"Event"},
     *      @SWG\Response(
     *          response="200",
     *          description="Array of Event objects",
     *          ref="$/responses/200",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="items",
     *                  type="array",
     *                  @SWG\Items(
     *                      @SWG\Property(property="id", type="integer"),
     *                      @SWG\Property(property="title", type="string"),
     *                  ),
     *             )
     *          )
     *      ),
     * )
     */
    public function dropdownFuture()
    {
        
        $spaces = $this->eventRepository->findWhere([['date_time', '>', Carbon::now()]], ['id', 'title'])->toArray();
    
        foreach ($spaces as $i => $space) {
            unset($spaces[ $i ][ 'image_url' ]);
        }
        
        return $this->sendResponse($spaces, 'Future events retrieved successfully');
    }
    
    /**
     * @param int $id
     *
     * @return Response
     *
     * @SWG\Get(
     *      path="/events/get_default/{id}",
     *      summary="Display the default Event for the specified restaurant space",
     *      tags={"Event"},
     *      @SWG\Parameter(ref="$/parameters/id_in_path_required", description="id of Restaurant space"),
     *      @SWG\Response(response=200, ref="#/responses/Event"),
     *      @SWG\Response(response="404", description="Event not found"),
     * )
     */
    public function defaultEvent($restaurant_space_id)
    {
        
        $event = $this->eventRepository->defaultEvent($restaurant_space_id);
        
        if (empty($event)) {
            return $this->sendError('Event not found');
        }
        
        return $this->sendResponse($event->toArray(), 'Event retrieved successfully');
    }
}
