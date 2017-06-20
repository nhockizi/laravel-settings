<?php

namespace Kizi\Settings\Controllers;

use Illuminate\Routing\Controller;
use Kizi\Settings\Auth\Database\Menu;
use Kizi\Settings\Auth\Database\Role;
use Kizi\Settings\Facades\Admin;
use Kizi\Settings\Form;
use Kizi\Settings\Layout\Column;
use Kizi\Settings\Layout\Content;
use Kizi\Settings\Layout\Row;
use Kizi\Settings\Tree;
use Kizi\Settings\Widgets\Box;

class MenuController extends Controller
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
            $content->header(trans('admin::lang.menu'));
            $content->description(trans('admin::lang.list'));

            $content->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \Kizi\Settings\Widgets\Form();
                    $form->action(Admin::url('menu'));

                    $form->select('parent_id', trans('admin::lang.parent_id'))->options(Menu::selectOptions());
                    $form->text('title', trans('admin::lang.title'))->rules('required');
                    $form->icon('icon', trans('admin::lang.icon'))->default('fa-bars')->rules('required')->help($this->iconHelp());
                    $form->text('uri', trans('admin::lang.uri'));
                    $form->multipleSelect('roles', trans('admin::lang.roles'))->options(Role::all()->pluck('name', 'id'));

                    $column->append((new Box(trans('admin::lang.new'), $form))->style('success'));
                });
            });
        });
    }

    /**
     * Redirect to edit page.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        return redirect()->action(
            '\Kizi\Settings\Controllers\MenuController@edit', ['id' => $id]
        );
    }

    /**
     * @return \Kizi\Settings\Tree
     */
    protected function treeView()
    {
        return Menu::tree(function (Tree $tree) {
            $tree->branch(function ($branch) {
                $payload = "<i class='fa {$branch['icon']}'></i>&nbsp;<strong>{$branch['title']}</strong>";

                // if (!isset($branch['children'])) {
                //     $uri = Admin::url($branch['uri']);

                //     $payload .= "&nbsp;&nbsp;&nbsp;<a href=\"$uri\" class=\"dd-nodrag\">{$branch['title']}</a>";
                // }

                return $payload;
            });
        });
    }

    /**
     * Edit interface.
     *
     * @param string $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header(trans('admin::lang.menu'));
            $content->description(trans('admin::lang.edit'));

            $content->row($this->form()->edit($id));
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Menu::form(function (Form $form) {
            $form->display('id', 'ID');

            $form->select('parent_id', trans('admin::lang.parent_id'))->options(Menu::selectOptions());
            $form->text('title', trans('admin::lang.title'))->rules('required');
            $form->icon('icon', trans('admin::lang.icon'))->default('fa-bars')->rules('required')->help($this->iconHelp());
            $form->text('uri', trans('admin::lang.uri'));
            $form->multipleSelect('roles', trans('admin::lang.roles'))->options(Role::all()->pluck('name', 'id'));

            $form->display('created_at', trans('admin::lang.created_at'));
            $form->display('updated_at', trans('admin::lang.updated_at'));
        });
    }

    /**
     * Help message for icon field.
     *
     * @return string
     */
    protected function iconHelp()
    {
        return 'For more icons please see <a href="http://fontawesome.io/icons/" target="_blank">http://fontawesome.io/icons/</a>';
    }
}
