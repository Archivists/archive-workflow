<?php
/*
|--------------------------------------------------------------------------
| Category Controller
|--------------------------------------------------------------------------
| See routes.php  ->
| Route::resource('category', 'CategoryController');
| Route::get('category/{category}/delete', 'CategoryController@delete');
|
*/

class CategoryController extends BaseController
{
    /**
     * Category Model
     * @var Category
     */
    protected $category;

    /**
     * Inject the model.
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        parent::__construct();
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * See public function data() below for the data source for the list,
     * and the view/category/index.blade.php for the jQuery script that makes
     * the Ajax request.
     *
     * @return Response
     */
    public function index()
    {
        // Title
        $title = Lang::get('category/title.category_management');

        // Show the page
        return View::make('category/index', compact('title'));
    }

    /**
     * Show a single category details page.
     *
     * @return View
     */
    public function show($id)
    {
        $category = $this->category->find($id);

        if ($category->id) {
            // Title
            $title = Lang::get('category/title.category_show');

            // Show the page
            return View::make('category/show', compact('category', 'title'));

        } else {
            // Redirect to the category management page
            return Redirect::to('admin/categories')->with('error', Lang::get('category/messages.does_not_exist'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Title
        $title = Lang::get('category/title.create_a_new_category');

        // Show the page
        return View::make('category/create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        // Validate the inputs
        $rules = array(
            'name'=> 'required|unique:categories,name',
            'description'=> 'required'
            );

        // Validate the inputs
        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes()) {
            // Get the inputs, with some exceptions
            $inputs = Input::except('csrf_token');

            $this->category->name = $inputs['name'];
            $this->category->description = $inputs['description'];
            
            if ($this->category->save($rules)) {
                // Redirect to the new category page
                return Redirect::to('admin/categories')->with('success', Lang::get('category/messages.create.success'));

            } else {
                // Redirect to the category create page
                //var_dump($this->category);
                return Redirect::to('admin/categories/create')->with('error', Lang::get('category/messages.create.error'));
            }
        } else {
            // Form validation failed
            return Redirect::to('admin/categories/create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $category
     * @return Response
     */
    public function edit($id)
    {
        $category = $this->category->find($id);

        if ($category->id) {

        } else {
            // Redirect to the category management page
            return Redirect::to('admin/categories')->with('error', Lang::get('category/messages.does_not_exist'));
        }

        // Title
        $title = Lang::get('category/title.category_update');

        // Show the page
        return View::make('category/edit', compact('category', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $category
     * @return Response
     */
    public function update($id)
    {
        $category = $this->category->find($id);

        $rules = array(
                'name'=> 'required|unique:categories,name,' . $category->id,
                'description' => 'required'
            );

        // Validate the inputs
        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes()) {
            $category->name        = Input::get('name');
            $category->description = Input::get('description');

            // Was the category updated?
            if ($category->save($rules)) {
                // Redirect to the category page
                return Redirect::to('admin/categories/' . $category->id . '/edit')->with('success', Lang::get('category/messages.update.success'));
            } else {
                // Redirect to the category page
                return Redirect::to('admin/categories/' . $category->id . '/edit')->with('error', Lang::get('category/messages.update.error'));
            }
        } else {
            // Form validation failed
            return Redirect::to('admin/categories/' . $category->id . '/edit')->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove category page.
     *
     * @param $category
     * @return Response
     */
    public function delete($id)
    {
        $category = $this->category->find($id);

        // Title
        $title = Lang::get('category/title.category_delete');

        if ($category->id) {

        } else {
            // Redirect to the category management page
            return Redirect::to('admin/categories')->with('error', Lang::get('category/messages.does_not_exist'));
        }

        // Show the record
        return View::make('category/delete', compact('category', 'title'));
    }

    /**
     * Remove the specified category from storage.
     * @internal param $id
     * @return Response
     */
    public function destroy($id)
    {
        $category = $this->category->find($id);

        // Was the category deleted?
        if ($category->delete()) {
            // Redirect to the category management page
            return Redirect::to('admin/categories')->with('success', Lang::get('category/messages.delete.success'));
        }

        // There was a problem deleting the category
        return Redirect::to('admin/categories')->with('error', Lang::get('category/messages.delete.error'));
    }

    /**
     * Show a list of all the admin/categories formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        //Make this method testable and mockable by using our injected $category member.
        $categories = $this->category->select(array('categories.id',  'categories.name', 'categories.description', 'categories.created_at'));

        return Datatables::of($categories)
        // ->edit_column('created_at','{{{ Carbon::now()->diffForHumans(Carbon::createFromFormat(\'Y-m-d H\', $test)) }}}')

        ->add_column('actions', '<div class="btn-group">
                  <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">
                    Action <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="{{{ URL::to(\'admin/categories/\' . $id ) }}}">{{{ Lang::get(\'button.show\') }}}</a></li>
                    <li><a href="{{{ URL::to(\'admin/categories/\' . $id . \'/edit\' ) }}}">{{{ Lang::get(\'button.edit\') }}}</a></li>
                    <li><a href="{{{ URL::to(\'admin/categories/\' . $id . \'/delete\' ) }}}">{{{ Lang::get(\'button.delete\') }}}</a></li>
                  </ul>
                </div>')

        ->remove_column('id')

        ->make();
    }
}
