<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;

class BlogController extends Controller
{
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
     *      @OA\Response(response="422", description="Unprocessable entity. Indicates that the request body is invalid or incomplete."),
     *      @OA\Response(response="500", description="Internal server error. Indicates a server-side problem."),
     *      @OA\Parameter(
     *          name="Accept",
     *          required="true",
     *          in="header",
     *          description="Accept header",
     *          @OA\Schema(type="string", default="application/json")
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
}
