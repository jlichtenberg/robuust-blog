<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;

class BlogController extends Controller
{

    /**
     * Get all blogs.
     * 
     * @return JsonResponse
     *
     * @OA\Get(
     *      path="/api/blogs",
     *      summary="Get all blogs",
     *      description="Returns a list of all blogs.",
     *      tags={"Blogs"},
     *      @OA\Response(response="200", description="A list of all blogs"),
     *      @OA\Response(response="401", description="Unauthorized. Indicates that the user is not authenticated."),
     *      @OA\Response(response="500", description="Internal server error. Indicates a server-side problem."),
     *      @OA\Parameter(
     *          name="Accept",
     *          required=true,
     *          in="header",
     *          description="Accept header",
     *          @OA\Schema(type="string", default="application/json")
     *      ),
     *      @OA\Parameter(
     *          name="Authentication",
     *          required=true,
     *          in="header",
     *          description="Bearer token",
     *          @OA\Schema(type="string", default="Bearer <token>")
     *      )
     * )
     */
    public function index(): JsonResponse
    {
        $blogs = Blog::with('user')->orderByDesc('created_at')->get();

        if ($blogs->isEmpty()) {
            return response()->json([
                'message' => 'Er zijn geen blogs gevonden.'
            ], 200);
        }

        return response()->json([
            'blogs' => $blogs
        ], 200);
    }

    /**
     * Create a new blog.
     * 
     * @param BlogRequest $request
     * @return JsonResponse
     *
     * @OA\Post(
     *      path="/api/blogs",
     *      summary="Create a new blog",
     *      description="Creates a new blog with the provided title, image, and body.",
     *      tags={"Blogs"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Blog information",
     *          @OA\JsonContent(
     *              required={"title", "image", "body"},
     *              @OA\Property(property="title", type="string", example="My first blog", description="The title of the blog"),
     *              @OA\Property(property="image", type="string", format="url", example="https://example.com/image.jpg", description="The image of the blog"),
     *              @OA\Property(property="body", type="string", example="This is the body of my first blog", description="The body of the blog")
     *          )
     *      ),
     *      @OA\Response(response="201", description="Blog created successfully"),
     *      @OA\Response(response="400", description="Bad request. Indicates that the request body is invalid or incomplete."),
     *      @OA\Response(response="401", description="Unauthorized. Indicates that the user is not authenticated."),
     *      @OA\Response(response="422", description="Unprocessable entity. Indicates that the request body is invalid or incomplete."),
     *      @OA\Response(response="500", description="Internal server error. Indicates a server-side problem."),
     *      @OA\Parameter(
     *          name="Accept",
     *          required=true,
     *          in="header",
     *          description="Accept header",
     *          @OA\Schema(type="string", default="application/json")
     *      ),
     *      @OA\Parameter(
     *          name="Authentication",
     *          required=true,
     *          in="header",
     *          description="Bearer token",
     *          @OA\Schema(type="string", default="Bearer <token>")
     *      )
     * )
     */

    public function create(BlogRequest $request): JsonResponse
    {
        $blog = Blog::create([
            'title' => $request->title,
            'image' => $request->image,
            'body' => $request->body,
            'user_id' => auth()->user()->id
        ]);

        return response()->json([
            'message' => 'De blog is succesvol aangemaakt.',
            'blog' => $blog
        ], 201);
    }

    /**
     * Get a blog by ID.
     * 
     * @param int $id
     * @return JsonResponse
     * 
     * @OA\Get(
     *     path="/api/blogs/{id}",
     *      summary="Get a blog by ID",
     *      description="Returns a blog with the provided ID.",
     *      tags={"Blogs"},
     *      @OA\Response(response="200", description="A blog with the provided ID"),
     *      @OA\Response(response="404", description="Not found. Indicates that the blog with the provided ID was not found."),
     *      @OA\Response(response="401", description="Unauthorized. Indicates that the user is not authenticated."),
     *      @OA\Response(response="500", description="Internal server error. Indicates a server-side problem."),
     *      @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          description="The ID of the blog",
     *          @OA\Schema(type="integer")
     *      ),
     *       @OA\Parameter(
     *          name="Accept",
     *          required=true,
     *          in="header",
     *          description="Accept header",
     *          @OA\Schema(type="string", default="application/json")
     *      ),
     *      @OA\Parameter(
     *          name="Authentication",
     *          required=true,
     *          in="header",
     *          description="Bearer token",
     *          @OA\Schema(type="string", default="Bearer <token>")
     *      )
     * )
     */
    public function show($id): JsonResponse
    {
        $blog = Blog::where('id', $id)->with('user')->first();

        if (!$blog) {
            return response()->json([
                'message' => 'Geen blog gevonden met dit ID'
            ], 404);
        }

        return response()->json([
            'blog' => $blog
        ], 200);
    }

    
}
