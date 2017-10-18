<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Company;
use App\Models\CompanyMember;
use App\Models\Count;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Chart\Bar;
use Encore\Admin\Widgets\Chart\Doughnut;
use Encore\Admin\Widgets\Chart\Line;
use Encore\Admin\Widgets\Chart\Pie;
use Encore\Admin\Widgets\Chart\PolarArea;
use Encore\Admin\Widgets\Chart\Radar;
use Encore\Admin\Widgets\Collapse;
use Encore\Admin\Widgets\InfoBox;
use Encore\Admin\Widgets\Tab;
use Encore\Admin\Widgets\Table;


class UserCountController extends Controller
{
    use ModelForm;

    public function show($id)
    {


        return Admin::content(function (Content $content) use ($id) {


            $company = Company::where('user_id',$id)->pluck('id');
            $channel_ids = Channel::whereIn('id',$company)->pluck('id');

            $count = Count::whereIn('channel_id', $channel_ids);

            $content->header('用户管理');
            $content->description('用户统计');
            $content->body('<style>.small-box-footer{display:none}</style>');

            $content->row(function ($row) use ($count) {
                $ip = $count->groupBy('ip')->count();
                $pv = $count->groupBy('openid')->count();
                $uv = $count->count();
                $row->column(4, new InfoBox('用户量', 'users', 'blue', '', $ip + 1));
                $row->column(4, new InfoBox('观看人次', 'image', 'green', '', $pv + 1));
                $row->column(4, new InfoBox('IP个数', 'book', 'yellow', '', $uv + 1));

            });


            $content->row(function (Row $row) use ($count) {

                $row->column(12, function (Column $column) use ($count) {
                    $all = $count->count();
                    $android = $count->where('ua', 'android')->count();
                    $ios = $count->where('ua', 'ios')->count();


                    $pie = new Pie([
                        ['Android', $android + 1], ['IOS', $ios + 1], ['其他', $all - $ios - $android + 1]
                    ]);

                    $column->append(new Box('系统分布', $pie));


                });


            });


        });
    }
}
