<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class VendorController extends Controller
{
    #[OA\Get(
        path: '/vendors/user/{id}',
        summary: 'Get vendor by user ID',
        tags: ['Vendors'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'User ID', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daftar vendor milik user',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Get vendor by user'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'user_id', type: 'integer', example: 1),
                                    new OA\Property(property: 'name', type: 'string', example: 'My Event Organizer'),
                                    new OA\Property(property: 'location', type: 'string', example: 'Jl. Sudirman No. 1'),
                                    new OA\Property(property: 'phone', type: 'string', example: '08123456789'),
                                    new OA\Property(property: 'city', type: 'string', example: 'Jakarta'),
                                    new OA\Property(property: 'verify_status', type: 'string', enum: ['pending', 'approved', 'rejected'], example: 'approved'),
                                ],
                                type: 'object'
                            )
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function getVendorByUser($id)
    {
        $vendor = \App\Models\Vendor::where('user_id', $id)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Get vendor by user',
            'data' => $vendor
        ], 200);
    }

    #[OA\Post(
        path: '/vendor',
        summary: 'Register sebagai vendor',
        description: 'Membuat vendor baru dan mengubah is_vendor user menjadi 1.',
        tags: ['Vendors'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['user_id', 'name', 'description', 'location', 'phone', 'city'],
                properties: [
                    new OA\Property(property: 'user_id', type: 'integer', example: 1),
                    new OA\Property(property: 'name', type: 'string', example: 'My Event Organizer'),
                    new OA\Property(property: 'description', type: 'string', example: 'Event organizer terbaik'),
                    new OA\Property(property: 'location', type: 'string', example: 'Jl. Sudirman No. 1'),
                    new OA\Property(property: 'phone', type: 'string', example: '08123456789'),
                    new OA\Property(property: 'city', type: 'string', example: 'Jakarta'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Vendor berhasil dibuat',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Vendor created successfully'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer'),
                                new OA\Property(property: 'user_id', type: 'integer'),
                                new OA\Property(property: 'name', type: 'string'),
                                new OA\Property(property: 'description', type: 'string'),
                                new OA\Property(property: 'location', type: 'string'),
                                new OA\Property(property: 'phone', type: 'string'),
                                new OA\Property(property: 'city', type: 'string'),
                                new OA\Property(property: 'verify_status', type: 'string', example: 'pending'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function createVendor(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'location' => 'required',
            'phone' => 'required',
            'city' => 'required',
        ]);

        $data = $request->all();
        $data['verify_status'] = 'pending';

        $existingVendor = \App\Models\Vendor::where('user_id', $data['user_id'])->first();
        if ($existingVendor) {
            return response()->json([
                'status' => 'error',
                'message' => 'User already registered as vendor',
            ], 422);
        }

        $user = \App\Models\User::find($data['user_id']);
        $user->is_vendor = 1;
        $user->save();

        $vendor = \App\Models\Vendor::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Vendor created successfully',
            'data' => $vendor
        ], 201);
    }
}
