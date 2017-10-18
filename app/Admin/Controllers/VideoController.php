<?php

namespace App\Admin\Controllers;


use App\Models\Video;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class VideoController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('素材库管理');
            $content->description('Material');

            $content->body($this->grid() . '<style>.fa-edit {display: none}</style>');

        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('素材编辑');
            $content->description('Material');

            $content->body($this->form()->edit($id));

        });
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Video::class, function (Grid $grid) {

            $grid->disableCreation();
            $grid->disableExport();
            $grid->title('标题');
            $grid->duration('时长')->display(function ($duration) {
                $sec = ($duration / 1000 % 60);
                $sec = $sec > 9 ? $sec : '0' . $sec;
                $sec = (int)($duration / 60000) . ":" . $sec;
                return $sec;
            });
            $grid->thumbnaill('图片')->display(function ($thumbnaill) {
                return "<img width='40px' src='" . $thumbnaill . "'>";
            });
            $grid->date('时间');
            $grid->actions(function ($actions) {

                // append一个操作
                $actions->append('<a target="_blank" href="' . $actions->row->flashUrl . '"><i class="fa fa-eye"></i></a>');

            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Video::class, function (Form $form) {
        });
    }

    public function destroy($id)
    {
        $video = Video::find($id);

        $res = \App\Http\Live\Video::deleteVideo($video->video_id);

        if ($res->code == 0) {

            if ($this->form()->destroy($id)) {

                return response()->json([
                    'status' => true,
                    'message' => trans('admin::lang.delete_succeeded'),
                ]);
            }

        } else {
            return response()->json([
                'status' => false,
                'message' => trans('admin::lang.delete_failed'),
            ]);
        }
    }
}
