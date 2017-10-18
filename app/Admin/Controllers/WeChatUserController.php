<?php

namespace App\Admin\Controllers;

use App\Models\WeChatUser;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\Hash;

class WeChatUserController extends Controller
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

            $content->header('微信用户管理');
            $content->description('User');

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

            $content->header('用户编辑');
            $content->description('User');

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

            $content->header('新建用户');
            $content->description('User');

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
        return Admin::grid(WeChatUser::class, function (Grid $grid) {

            $grid->openid('Openid')->sortable();
            $grid->nickname('昵称')->sortable();
            $grid->email('邮箱')->sortable();
            $grid->sex('性别')->display(function ($sex){
                return $sex ? '男' : '女';
            });
            $grid->province('省份');
            $grid->city('城市');
            $grid->headimgurl('头像')->display(function ($headimgurl){
                return '<img height="25px" src="'.$headimgurl.'">';
            });

            $grid->filter(function ($filter) {
                $filter->useModal();
                $filter->disableIdFilter();
                $filter->like('name', '姓名');
                $filter->like('email', '邮箱');
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
        return Admin::form(WeChatUser::class, function (Form $form) {

            $form->text('email', '邮箱')->rules('required');
            $form->text('nickname', '昵称')->rules('required');
            $form->text('province', '省份')->rules('required');
            $form->text('city', '城市')->rules('required');
            $form->display('created_at', '注册时间');
            $form->display('updated_at', '修改时间');

        });
    }
}
