<?php
/*
|--------------------------------------------------------------------------
| Status Controller
|--------------------------------------------------------------------------
| See routes.php  ->
| Route::resource('status', 'StatusController');
| Route::get('status/{statusID}/delete', 'StatusController@delete');
|
*/

class StatusController extends BaseController
{
    /**
     * Status Model
     * @var Status
     */
    protected $status;

    /**
     * Inject the model.
     * @param Status $status
     */
    public function __construct(Status $status)
    {
        parent::__construct();
        $this->status = $status;
    }

    /**
     * Display a listing of the resource.
     *
     * See public function data() below for the data source for the list,
     * and the view/status/index.blade.php for the jQuery script that makes
     * the Ajax request.
     *
     * @return Response
     */
    public function index()
    {
        // Title
        $title = Lang::get('status/title.status_management');

        // Show the page
        return View::make('status/index', compact('title'));
    }

    /**
     * Show a single status details page.
     *
     * @return View
     */
    public function show($id)
    {
        $status = $this->status->find($id);

        if ($status->id) {
            // Title
            $title = Lang::get('status/title.status_show');

            // Show the page
            return View::make('status/show', compact('status', 'title'));

        } else {
            // Redirect to the status management page
            return Redirect::to('status')->with('error', Lang::get('status/messages.does_not_exist'));
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
        $title = Lang::get('status/title.create_a_new_status');

        // Show the page
        return View::make('status/create', compact('title'));
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
            'name'=> 'required|unique:status,name',
            'description'=> 'required'
            );

        // Validate the inputs
        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes()) {
            // Get the inputs, with some exceptions
            $inputs = Input::except('csrf_token');

            $this->status->name = $inputs['name'];
            $this->status->description = $inputs['description'];
            
            if ($this->status->save($rules)) {
                // Redirect to the new status page
                return Redirect::to('status')->with('success', Lang::get('status/messages.create.success'));

            } else {
                // Redirect to the status create page
                //var_dump($this->status);
                return Redirect::to('status/create')->with('error', Lang::get('status/messages.create.error'));
            }
        } else {
            // Form validation failed
            return Redirect::to('status/create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $status
     * @return Response
     */
    public function edit($id)
    {
        $status = $this->status->find($id);

        if ($status->id) {

        } else {
            // Redirect to the status management page
            return Redirect::to('status')->with('error', Lang::get('status/messages.does_not_exist'));
        }

        // Title
        $title = Lang::get('status/title.status_update');

        // Show the page
        return View::make('status/edit', compact('status', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $status
     * @return Response
     */
    public function update($id)
    {
        $status = $this->status->find($id);

        $rules = array(
                'name'=> 'required|unique:status,name,' . $status->id,
                'description' => 'required'
            );

        // Validate the inputs
        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes()) {
            $status->name        = Input::get('name');
            $status->description = Input::get('description');

            // Was the status updated?
            if ($status->save($rules)) {
                // Redirect to the status page
                return Redirect::to('status/' . $status->id . '/edit')->with('success', Lang::get('status/messages.update.success'));
            } else {
                // Redirect to the status page
                return Redirect::to('status/' . $status->id . '/edit')->with('error', Lang::get('status/messages.update.error'));
            }
        } else {
            // Form validation failed
            return Redirect::to('status/' . $status->id . '/edit')->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove status page.
     *
     * @param $status
     * @return Response
     */
    public function delete($id)
    {
        $status = $this->status->find($id);

        // Title
        $title = Lang::get('status/title.status_delete');

        if ($status->id) {

        } else {
            // Redirect to the status management page
            return Redirect::to('status')->with('error', Lang::get('status/messages.does_not_exist'));
        }

        // Show the record
        return View::make('status/delete', compact('status', 'title'));
    }

    /**
     * Remove the specified status from storage.
     * @internal param $id
     * @return Response
     */
    public function destroy($id)
    {
        $status = $this->status->find($id);

        // Was the status deleted?
        if ($status->delete()) {
            // Redirect to the status management page
            return Redirect::to('status')->with('success', Lang::get('status/messages.delete.success'));
        }

        // There was a problem deleting the status
        return Redirect::to('status')->with('error', Lang::get('status/messages.delete.error'));
    }

    /**
     * Show a list of all the status formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        //Make this method testable and mockable by using our injected $status member.
        $status = $this->status->select(array('status.id',  'status.name', 'status.description', 'status.created_at'));

        return Datatables::of($status)
        // ->edit_column('created_at','{{{ Carbon::now()->diffForHumans(Carbon::createFromFormat(\'Y-m-d H\', $test)) }}}')

        ->add_column('actions', '<div class="btn-group">
                  <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">
                    Action <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="{{{ URL::to(\'status/\' . $id ) }}}">{{{ Lang::get(\'button.show\') }}}</a></li>
                    <li><a href="{{{ URL::to(\'status/\' . $id . \'/edit\' ) }}}">{{{ Lang::get(\'button.edit\') }}}</a></li>
                    <li><a href="{{{ URL::to(\'status/\' . $id . \'/delete\' ) }}}">{{{ Lang::get(\'button.delete\') }}}</a></li>
                  </ul>
                </div>')

        ->remove_column('id')

        ->make();
    }
}
