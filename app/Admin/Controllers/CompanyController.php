<?php

namespace App\Admin\Controllers;


use App\Models\Company;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class CompanyController extends Controller
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

            $content->header('组织管理');
            $content->description('Company');

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

            $content->header('组织编辑');
            $content->description('Company');

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

            $content->header('新建编辑');
            $content->description('Company');

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
        return Admin::grid(Company::class, function (Grid $grid) {


            $grid->name('频道组织')->sortable();

            $grid->logo('组织Logo')->image();

            $grid->created_at('创建时间');

            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->like('name', '频道名称');
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
        return Admin::form(Company::class, function (Form $form) {

            $form->text('name', '频道名称')->rules('required');

            $form->image('logo', '频道Logo');

        });
    }
}
