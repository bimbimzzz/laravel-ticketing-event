<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\EventCategory;
use App\Models\Vendor;
use App\Helpers\DemoHelper;
use Illuminate\Support\Facades\Gate;
use OpenApi\Attributes as OA;

class EventController extends Controller
{
    private function formatEventsWithGroupedTickets($events)
    {
        return $events->map(function ($event) {
            $groupedTickets = $event->tickets
                ->filter(fn($ticket) => $ticket->status === 'available')
                ->groupBy('sku_id')
                ->map(function ($tickets) {
                    $sku = $tickets->first()->sku;
                    return [
                        'sku' => [
                            'id' => $sku->id,
                            'name' => $sku->name,
                            'category' => $sku->category,
                            'price' => $sku->price,
                            'stock' => $sku->stock,
                            'day_type' => $sku->day_type,
                        ],
                        'ticket_count' => $tickets->count(),
                    ];
                })->values();

            return [
                'id' => $event->id,
                'name' => $event->name,
                'description' => $event->description,
                'image' => $event->image,
                'start_date' => $event->start_date,
                'end_date' => $event->end_date,
                'vendor' => $event->vendor,
                'event_category' => $event->eventCategory,
                'tickets' => $groupedTickets,
            ];
        });
    }

    #[OA\Get(
        path: '/events',
        summary: 'Get semua events (public)',
        description: 'Menampilkan semua event dengan grouped tickets per SKU. Filter opsional via category_id.',
        tags: ['Events'],
        parameters: [
            new OA\Parameter(
                name: 'category_id',
                in: 'query',
                required: false,
                description: 'Filter by category ID, atau "all" untuk semua',
                schema: new OA\Schema(type: 'string', example: 'all')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daftar events dengan grouped tickets',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'name', type: 'string', example: 'Jazz Festival 2026'),
                                    new OA\Property(property: 'description', type: 'string'),
                                    new OA\Property(property: 'image', type: 'string', nullable: true, example: '1738850657.png'),
                                    new OA\Property(property: 'start_date', type: 'string', format: 'date', example: '2026-04-01'),
                                    new OA\Property(property: 'end_date', type: 'string', format: 'date', example: '2026-04-02'),
                                    new OA\Property(
                                        property: 'vendor',
                                        type: 'object',
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer'),
                                            new OA\Property(property: 'user_id', type: 'integer'),
                                            new OA\Property(property: 'name', type: 'string'),
                                        ]
                                    ),
                                    new OA\Property(
                                        property: 'event_category',
                                        type: 'object',
                                        nullable: true,
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer'),
                                            new OA\Property(property: 'name', type: 'string', example: 'Musik'),
                                        ]
                                    ),
                                    new OA\Property(
                                        property: 'tickets',
                                        type: 'array',
                                        description: 'Tiket di-group per SKU, hanya yang available',
                                        items: new OA\Items(
                                            properties: [
                                                new OA\Property(
                                                    property: 'sku',
                                                    type: 'object',
                                                    properties: [
                                                        new OA\Property(property: 'id', type: 'integer'),
                                                        new OA\Property(property: 'name', type: 'string', example: 'VIP'),
                                                        new OA\Property(property: 'category', type: 'string', example: 'Premium'),
                                                        new OA\Property(property: 'price', type: 'integer', example: 150000),
                                                        new OA\Property(property: 'stock', type: 'integer', example: 100),
                                                        new OA\Property(property: 'day_type', type: 'string', example: 'weekday'),
                                                    ]
                                                ),
                                                new OA\Property(property: 'ticket_count', type: 'integer', example: 50, description: 'Jumlah tiket available untuk SKU ini'),
                                            ],
                                            type: 'object'
                                        )
                                    ),
                                ],
                                type: 'object'
                            )
                        ),
                    ]
                )
            ),
        ]
    )]
    public function index(Request $request)
    {
        $categoryId = $request->input('category_id');

        $query = Event::with(['vendor', 'eventCategory', 'tickets.sku']);
        if ($categoryId && $categoryId != 'all') {
            $query->where('event_category_id', $categoryId);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $events = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $this->formatEventsWithGroupedTickets($events),
        ]);
    }

    #[OA\Get(
        path: '/event-categories',
        summary: 'Get semua event categories',
        tags: ['Events'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daftar kategori event',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Event categories fetched successfully'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'name', type: 'string', example: 'Musik'),
                                    new OA\Property(property: 'description', type: 'string', nullable: true),
                                ],
                                type: 'object'
                            )
                        ),
                    ]
                )
            ),
        ]
    )]
    public function categories()
    {
        $categories = EventCategory::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Event categories fetched successfully',
            'data' => $categories,
        ]);
    }

    #[OA\Get(
        path: '/event/{event_id}',
        summary: 'Detail event dengan SKUs',
        tags: ['Events'],
        parameters: [
            new OA\Parameter(name: 'event_id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Detail event',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer'),
                                new OA\Property(property: 'name', type: 'string'),
                                new OA\Property(property: 'description', type: 'string'),
                                new OA\Property(property: 'image', type: 'string', nullable: true),
                                new OA\Property(property: 'start_date', type: 'string', format: 'date'),
                                new OA\Property(property: 'end_date', type: 'string', format: 'date'),
                                new OA\Property(property: 'vendor', type: 'object'),
                                new OA\Property(property: 'event_category', type: 'object'),
                                new OA\Property(
                                    property: 'skus',
                                    type: 'array',
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer'),
                                            new OA\Property(property: 'name', type: 'string', example: 'VIP'),
                                            new OA\Property(property: 'category', type: 'string'),
                                            new OA\Property(property: 'price', type: 'integer'),
                                            new OA\Property(property: 'stock', type: 'integer'),
                                            new OA\Property(property: 'day_type', type: 'string'),
                                        ],
                                        type: 'object'
                                    )
                                ),
                            ]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function detail(Request $request)
    {
        $event = Event::find($request->event_id);
        if (!$event) {
            return response()->json([
                'status' => 'error',
                'message' => 'Event not found',
            ], 404);
        }
        $event->load('eventCategory', 'vendor');
        $skus = $event->skus;
        $event['skus'] = $skus;
        return response()->json([
            'status' => 'success',
            'message' => 'Event fetched successfully',
            'data' => $event,
        ]);
    }

    #[OA\Get(
        path: '/events/all',
        summary: 'Get semua events dengan grouped tickets (authenticated)',
        tags: ['Events'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Semua events dengan grouped tickets (format sama dengan GET /events)',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object', description: 'Same format as GET /events response data items')),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function getAllEvents()
    {
        $events = Event::with(['vendor', 'eventCategory', 'tickets.sku'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $this->formatEventsWithGroupedTickets($events),
        ]);
    }

    #[OA\Post(
        path: '/events',
        summary: 'Create event baru (vendor)',
        tags: ['Events'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['vendor_id', 'event_category_id', 'name', 'description', 'image', 'start_date', 'end_date'],
                    properties: [
                        new OA\Property(property: 'vendor_id', type: 'integer', example: 1),
                        new OA\Property(property: 'event_category_id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'Jazz Festival 2026'),
                        new OA\Property(property: 'description', type: 'string', example: 'Festival jazz terbesar'),
                        new OA\Property(property: 'image', type: 'string', format: 'binary', description: 'Image file (jpeg, png, jpg, webp, max 5MB)'),
                        new OA\Property(property: 'start_date', type: 'string', format: 'date', example: '2026-04-01'),
                        new OA\Property(property: 'end_date', type: 'string', format: 'date', example: '2026-04-02'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Event berhasil dibuat',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Event created successfully'),
                        new OA\Property(property: 'data', type: 'object', description: 'Event object'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function create(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required',
            'event_category_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $data = $request->all();

        $event = \App\Models\Event::create($data);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/events'), $filename);
            $event->image = $filename;
            $event->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Event created successfully',
            'data' => $event
        ], 201);
    }

    #[OA\Post(
        path: '/event/update/{event_id}',
        summary: 'Update event (vendor)',
        tags: ['Events'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'event_id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['vendor_id', 'event_category_id', 'name', 'description', 'start_date', 'end_date'],
                    properties: [
                        new OA\Property(property: 'vendor_id', type: 'integer'),
                        new OA\Property(property: 'event_category_id', type: 'integer'),
                        new OA\Property(property: 'name', type: 'string'),
                        new OA\Property(property: 'description', type: 'string'),
                        new OA\Property(property: 'image', type: 'string', format: 'binary', description: 'Optional new image'),
                        new OA\Property(property: 'start_date', type: 'string', format: 'date'),
                        new OA\Property(property: 'end_date', type: 'string', format: 'date'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Event updated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden — bukan pemilik event'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(Request $request, $id)
    {
        $request->validate([
            'vendor_id' => 'required',
            'event_category_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $event = \App\Models\Event::findOrFail($id);
        Gate::authorize('update', $event);

        $data = $request->all();
        $event->update($data);
        if ($request->hasFile('image')) {
            if ($event->image) {
                $image_path = public_path('images/events/' . $event->image);
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/events'), $filename);
            $event->image = $filename;
            $event->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Event updated successfully',
            'data' => $event
        ], 200);
    }

    #[OA\Delete(
        path: '/event/{event_id}',
        summary: 'Delete event (vendor)',
        tags: ['Events'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'event_id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Event deleted',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Event deleted successfully'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
        ]
    )]
    public function delete($id)
    {
        if (DemoHelper::isDemoAccount()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun demo tidak dapat menghapus data.',
            ], 403);
        }

        $event = \App\Models\Event::findOrFail($id);
        Gate::authorize('delete', $event);
        $event->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Event deleted successfully',
        ]);
    }

    #[OA\Get(
        path: '/events/user/{id}',
        summary: 'Get events milik vendor (by user ID)',
        tags: ['Events'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'User ID (vendor)', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Events milik vendor dengan grouped tickets (format sama dengan GET /events)',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object', description: 'Same format as GET /events')),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function getEventByUser($id)
    {
        $vendors = Vendor::where('user_id', $id)->get();
        $eventIds = Event::whereIn('vendor_id', $vendors->pluck('id'))->pluck('id');

        $events = Event::with(['vendor', 'eventCategory', 'tickets.sku'])->whereIn('id', $eventIds)->get();
        return response()->json([
            'status' => 'success',
            'data' => $this->formatEventsWithGroupedTickets($events),
        ]);
    }
}
