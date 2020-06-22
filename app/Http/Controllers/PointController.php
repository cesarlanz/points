<?php

namespace App\Http\Controllers;

use App\Point;
use Illuminate\Http\Request;
use App\Http\Requests\PointRequest;
use App\Http\Resources\PointResource;
use Illuminate\Pagination\LengthAwarePaginator;


/**
* @OA\Info(title="API Points", version="1.0")
*
* @OA\Server(url="http://localhost:8000")
*/
class PointController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/points",
    *     operationId="index",
    *     tags={"Points"},
    *     summary="Get list of points",
    *     description="Returns list of points",
    *     @OA\Response(
    *         response=200,
    *         description="OK",
    *         @OA\JsonContent(),
    *     ),
    * ),
    */
    public function index()
    {
        return PointResource::collection(Point::paginate(15));
    }


    /**
    * @OA\Get(
    *     path="/api/points/{id}",
    *     operationId="show",
    *     tags={"Points"},
    *     summary="Get point information",
    *     description="Returns point data",
    *     @OA\Parameter(
    *          name="id",
    *          description="Point ID",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer",
    *          ),
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="OK",
    *         @OA\JsonContent(),
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Not Found",
    *         @OA\JsonContent(),
    *     )
    * )
    */
    public function show(Point $point)
    {
        return new PointResource($point);
    }


    /**
    * @OA\Post(
    *     path="/api/points",
    *     operationId="store",
    *     tags={"Points"},
    *     summary="Add a new point",
    *     description="Returns the new point created.",
    *     @OA\Response(
    *         response=201,
    *         description="Created",
    *         @OA\JsonContent(),
    *     ),
    *     @OA\Response(
    *         response=422,
    *         description="Unprocessable Entity",
    *         @OA\JsonContent(),
    *     ),
    *     @OA\RequestBody(
    *         @OA\JsonContent(
    *             type="object",
    *             @OA\Property(
    *                 property="x",
    *                 description="Coordinate at position X",
    *                 type="number",
    *             ),
    *             @OA\Property(
    *                 property="y",
    *                 description="Coordinate at position Y",
    *                 type="number"
    *             ),
    *         ),
    *     ),
    * )
    */
    public function store(PointRequest $request)
    {
        $point = Point::create($request->all());
        return new PointResource($point);
    }


    /**
    * @OA\Put(
    *     path="/api/points/{id}",
    *     operationId="update",
    *     tags={"Points"},
    *     summary="Update an existing point",
    *     description="Returns the point updated.",
    *     @OA\Parameter(
    *          name="id",
    *          description="Point ID",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          ),
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="OK",
    *         @OA\JsonContent(),
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Not Found",
    *         @OA\JsonContent(),
    *     ),
    *     @OA\Response(
    *         response=422,
    *         description="Unprocessable Entity",
    *         @OA\JsonContent(),
    *     ),
    *     @OA\RequestBody(
    *         @OA\JsonContent(
    *             type="object",
    *             @OA\Property(
    *                 property="x",
    *                 description="Coordinate at position X",
    *                 type="number",
    *             ),
    *             @OA\Property(
    *                 property="y",
    *                 description="Coordinate at position Y",
    *                 type="number"
    *             ),
    *         ),
    *     ),
    * )
    */
    public function update(PointRequest $request, Point $point)
    {
        $point->update($request->all());
        return new PointResource($point);
    }


    /**
    * @OA\Delete(
    *     path="/api/points/{id}",
    *     operationId="destroy",
    *     tags={"Points"},
    *     summary="Delete an existing point",
    *     description="Returns the point deleted.",
    *     @OA\Parameter(
    *          name="id",
    *          description="Point ID",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer",
    *          ),
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="OK",
    *         @OA\JsonContent(),
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Not Found",
    *         @OA\JsonContent(),
    *     ),
    * )
    */
    public function destroy(Point $point)
    {
        $delete = $point;
        $delete->delete();
        return new PointResource($point);
    }


    /**
    * @OA\Get(
    *     path="/api/points/{id}/nearby",
    *     operationId="nearby",
    *     tags={"Points"},
    *     summary="List the closest points.",
    *     description="Returns the N closest points defined by quantity",
    *     @OA\Parameter(
    *          name="id",
    *          description="Point ID",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer",
    *          ),
    *     ),
    *     @OA\Parameter(
    *          name="quantity",
    *          description="Limit the quantity of points returned",
    *          required=false,
    *          in="query",
    *          @OA\Schema(
    *              type="integer",
    *          ),
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="OK",
    *         @OA\JsonContent(),
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Not Found",
    *         @OA\JsonContent(),
    *     ),
    * )
    */
    public function nearby(Request $request, Point $point)
    {        
        $quantity = $request->input('quantity', 0);
        $points = $point->nearby($request->quantity);

        $page = $request->input('page', 1);
        $perPage = 15;
        $offset = ($page * $perPage) - $perPage;
        return PointResource::collection(new LengthAwarePaginator(
            array_slice($points, $offset, $perPage, true),
            count($points),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        ));
    }
}


