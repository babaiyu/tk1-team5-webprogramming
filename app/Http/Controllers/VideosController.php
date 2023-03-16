<?php

namespace App\Http\Controllers;

use App\Models\Videos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideosController extends Controller
{
    public function getVideos()
    {
        $videos = Videos::latest()->paginate(10);
        return response()->json($videos);
    }

    public function getVideoByID(Request $request, string $id)
    {
        $video = Videos::where('id', $id)->get();
        if ($video->count() > 0) {
            return response()
                ->json(array(
                    'result' => $video[0],
                    'url_video' => Storage::url('public/videos/') . $video[0]->video,
                ));
        }

        return response()->json($video);
    }

    public function postVideo(Request $request)
    {
        $file_video = $request->file('video');
        $file_video_name = time() . '_' . $file_video->getClientOriginalName();
        $file_video->storeAs('public/videos', $file_video_name);

        if ($file_video->isValid()) {
            $video = Videos::create([
                'title' => $request->title,
                'description' => $request->description,
                'video' => $file_video_name,
            ]);

            if ($video) {
                return response()
                    ->json(array(
                        'message' => 'Success post video!'
                    ));
            }

            return response()
                ->json(
                    array(
                        'message' => 'Failed to upload video!',
                    )
                )->setStatusCode(400);
        } else {
            return response()
                ->json(array('message' => 'Cannot upload the video, please try another way!'))
                ->setStatusCode(400);
        }
    }

    public function updateVideo(Request $request, string $id)
    {
        $video = Videos::findOrFail($id);

        $file_video = $request->file('video');
        $file_video_name = time() . '_' . $file_video->getClientOriginalName();
        $file_video->storeAs('public/videos', $file_video_name);

        if ($file_video->isValid()) {
            Storage::disk('local')->delete('public/videos/' . $video->video);

            $video->update([
                'title' => $request->title,
                'description' => $request->description,
                'video' => $file_video_name,
            ]);

            if ($video) {
                return response()
                    ->json(array(
                        'message' => 'Success update video',
                    ));
            } else {
                return response()
                    ->json(array(
                        'message' => 'Failed to update video, please try again!'
                    ))->setStatusCode(400);
            }
        }
    }

    public function deleteVideo(Request $request, string $id)
    {
        $video = Videos::findOrFail($id);
        Storage::disk('local')->delete(('public/videos/' . $video->video));
        $video->delete();

        if ($video) {
            return response()
                ->json(array('message' => 'Delete video is success!'));
        } else {
            return response()
                ->json(array('message' => 'Failed to delete video, please try again!'))
                ->setStatusCode(400);
        }
    }
}
