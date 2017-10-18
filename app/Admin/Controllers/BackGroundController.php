<?php

namespace App\Admin\Controllers;

use App\Models\Channel;
use App\Models\Image;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class BackGroundController extends Controller
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

            $content->header('背景图管理');
            $content->description('Background');

            $content->body($this->grid());

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

            $content->header('背景图编辑');
            $content->description('Background');

            $content->body($this->form()->edit($id));

        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('新建背景图');
            $content->description('Background');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Image::class, function (Grid $grid) {

            $grid->model()->where('type','background');

            $grid->channel_id('频道')->display(function ($channel_id){
                $channel = Channel::find($channel_id);
                if($channel) return $channel->name;
                return "";
            });
            $grid->url('图片')->display(function ($image){

                return '<img src="'.$image.'" style="max-width:50px;max-height:50px" class="img img-thumbnail">';

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
        return Admin::form(Image::class, function (Form $form) {
            $form->hidden('type')->default('background');
            $form->select('channel_id', '频道')->options(Channel::pluck('name','id'));
            $form->image('url','图片');

        });
    }
}

