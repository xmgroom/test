<?php

namespace App\Admin\Controllers;

use App\Http\Live\LiveChannel;
use App\Http\Live\UGC;
use App\Models\Channel;

use App\Models\Company;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;

class ChannelController extends Controller
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

            $content->header('频道管理');
            $content->description('Channel');

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

            $content->header('频道编辑');
            $content->description('Channel');

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

            $content->header('新建频道');
            $content->description('Channel');

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
        return Admin::grid(Channel::class, function (Grid $grid) {

            $grid->id('频道ID')->sortable();

            $grid->name('频道名称')->sortable();

            $grid->company_id('组织')->display(function ($company_id){
            
		if ($company = Company::find($company_id))
                    return $company->name;
                return '';
	    });

            $grid->content('频道简介')->display(function ($text) {
                return str_limit($text, 30, '...') . "";
            });
            $grid->logo('频道Logo')->display(function ($logo) {
                return "<img width='40px' src='" . $logo . "'>";
            });
            $grid->open('是否播放')->display(function ($open) {
                return $open ? '是' : '否';
            })->sortable();

            $grid->source('直播源')->display(function ($source) {
                return ($source == 1) ? '专业设备' : 'App';
            })->sortable();

            $grid->filter(function ($filter) {
                $filter->useModal();
                $filter->disableIdFilter();
                $filter->like('name', '频道名称');
                $filter->like('content', '频道简介');
                $filter->is('source', '直播源')->select([
                    1 => '专业设备',
                    2 => 'App'
                ]);
            });

            $grid->actions(function ($actions) {

                $actions->prepend('<a class="btn btn-sm btn-primary" href="/admin/channel_count/' . $actions->getKey() . '">统计信息</a>');
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
        return Admin::form(Channel::class, function (Form $form) {

            $form->text('name', '频道名称')->rules('required');
            $form->editor('content', '频道简介')->rules('required');
            $form->select('company_id', '所属组织')->options(Company::pluck('name', 'id'));
            $form->image('logo', '频道Logo');
            $form->image('live_logo', '视频Logo');
            $form->switch('open', '是否播放');
            $form->select('source', '直播源')->options([
                1 => '专业设备',
                2 => 'App'
            ]);


            $form->saved(function (Form $form) {

                if (!$form->model()->channel_id) {

                    $company = Company::create(['name' => $form->name, 'logo' => '/image/logo.png']);

                    $res = LiveChannel::liveCreateChannel($form->name);

                    if ($res->code == 0) {

                        $form->model()->update(['channel_id' => $res->result->id, 'company_id' => $company->id]);

                    }
                }

            });

        });
    }
}
