<?php

namespace App\Admin\Controllers;

use App\Models\Combos;
use App\Models\Company;
use App\Models\CompanyMember;
use App\Models\User;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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

            $content->header('用户管理');
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

            $content->body($this->form()->edit($id) .   "<script>
            var node = $('.fa-eye-slash').removeClass('fa-eye-slash').addClass('fa-lock');
                </script>");
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

            $content->body($this->form().   "<script>
            var node = $('.fa-eye-slash').removeClass('fa-eye-slash').addClass('fa-lock');
                </script>");
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(User::class, function (Grid $grid) {

            $grid->combo_id('套餐')->display(function ($combo_id){
               $combos =  Combos::find($combo_id);
               if($combos) return $combos->name;
               return "";
            });
            $grid->name('姓名')->sortable();
            $grid->email('邮箱')->sortable();
            $grid->created_at('注册时间')->sortable();

            $grid->filter(function ($filter) {
                $filter->useModal();
                $filter->disableIdFilter();
                $filter->like('name', '姓名');
                $filter->like('email', '邮箱');
            });

            $grid->actions(function ($actions) {
                $actions->prepend('<a class="btn btn-sm btn-primary" href="/admin/user_count/' . $actions->getKey() . '">统计信息</a>');
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
        return Admin::form(User::class, function (Form $form) {

            $form->select('combo_id','套餐')->options(Combos::pluck('name','id'));
            $form->text('name', '姓名')->rules('required');
            $form->text('email', '邮箱')->rules('required');
            $form->password('password', '密码')->rules('required');
            $form->display('created_at', '注册时间');
            $form->display('updated_at', '修改时间');

            $form->saved(function (Form $form) {

                if (strlen($form->model()->password) != 60) {

                    $form->model()->update(['password' => Hash::make($form->model()->password)]);

                }

            });

        });
    }
}
