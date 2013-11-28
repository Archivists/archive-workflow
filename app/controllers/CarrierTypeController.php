<?php
/*
|--------------------------------------------------------------------------
| CarrierType Controller
|--------------------------------------------------------------------------
| See routes.php  ->
| Route::resource('carrierType', 'CarrierTypeController');
| Route::get('carrierType/{carrierType}/delete', 'CarrierTypeController@delete');
|
*/

class CarrierTypeController extends BaseController
{
    /**
     * CarrierType Model
     * @var CarrierType
     */
    protected $carrierType;

    /**
     * Inject the model.
     * @param CarrierType $carrierType
     */
    public function __construct(CarrierType $carrierType)
    {
        parent::__construct();
        $this->carrierType = $carrierType;
    }

    /**
     * Display a listing of the resource.
     *
     * See public function data() below for the data source for the list,
     * and the view/carrierType/index.blade.php for the jQuery script that makes
     * the Ajax request.
     *
     * @return Response
     */
    public function index()
    {
        // Title
        $title = Lang::get('carrier-type/title.carrier-type_management');

        // Show the page
        return View::make('carrier-type/index', compact('title'));
    }

    /**
     * Show a single carrierType details page.
     *
     * @return View
     */
    public function show($id)
    {
        $carrierType = $this->carrierType->find($id);

        if ($carrierType->id) {
            // Title
            $title = Lang::get('carrier-type/title.carrier-type_show');

            // Show the page
            return View::make('carrier-type/show', compact('carrierType', 'title'));

        } else {
            // Redirect to the carrierType management page
            return Redirect::to('carrier-types')->with('error', Lang::get('carrier-type/messages.does_not_exist'));
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
        $title = Lang::get('carrier-type/title.create_a_new_carrier-type');

        // Show the page
        return View::make('carrier-type/create', compact('title'));
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
            'name'=> 'required|unique:carrier_types,name',
            'description'=> 'required'
            );

        // Validate the inputs
        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes()) {
            // Get the inputs, with some exceptions
            $inputs = Input::except('csrf_token');

            $this->carrierType->name = $inputs['name'];
            $this->carrierType->description = $inputs['description'];
            
            if ($this->carrierType->save($rules)) {
                // Redirect to the new carrierType page
                return Redirect::to('carrier-types')->with('success', Lang::get('carrier-type/messages.create.success'));

            } else {
                // Redirect to the carrierType create page
                //var_dump($this->carrierType);
                return Redirect::to('carrier-types/create')->with('error', Lang::get('carrier-type/messages.create.error'));
            }
        } else {
            // Form validation failed
            return Redirect::to('carrier-types/create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $carrierType
     * @return Response
     */
    public function edit($id)
    {
        $carrierType = $this->carrierType->find($id);

        if ($carrierType->id) {

        } else {
            // Redirect to the carrierType management page
            return Redirect::to('carrier-types')->with('error', Lang::get('carrier-type/messages.does_not_exist'));
        }

        // Title
        $title = Lang::get('carrier-type/title.carrier-type_update');

        // Show the page
        return View::make('carrier-type/edit', compact('carrierType', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $carrierType
     * @return Response
     */
    public function update($id)
    {
        $carrierType = $this->carrierType->find($id);

        $rules = array(
                'name'=> 'required|unique:carrier_types,name,' . $carrierType->id,
                'description' => 'required'
            );

        // Validate the inputs
        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes()) {
            $carrierType->name        = Input::get('name');
            $carrierType->description = Input::get('description');

            // Was the carrierType updated?
            if ($carrierType->save($rules)) {
                // Redirect to the carrierType page
                return Redirect::to('carrier-types/' . $carrierType->id . '/edit')->with('success', Lang::get('carrier-type/messages.update.success'));
            } else {
                // Redirect to the carrierType page
                return Redirect::to('carrier-types/' . $carrierType->id . '/edit')->with('error', Lang::get('carrier-type/messages.update.error'));
            }
        } else {
            // Form validation failed
            return Redirect::to('carrier-types/' . $carrierType->id . '/edit')->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove carrierType page.
     *
     * @param $carrierType
     * @return Response
     */
    public function delete($id)
    {
        $carrierType = $this->carrierType->find($id);

        // Title
        $title = Lang::get('carrier-type/title.carrier-type_delete');

        if ($carrierType->id) {

        } else {
            // Redirect to the carrierType management page
            return Redirect::to('carrier-types')->with('error', Lang::get('carrier-type/messages.does_not_exist'));
        }

        // Show the record
        return View::make('carrier-type/delete', compact('carrierType', 'title'));
    }

    /**
     * Remove the specified carrierType from storage.
     * @internal param $id
     * @return Response
     */
    public function destroy($id)
    {
        $carrierType = $this->carrierType->find($id);

        // Was the carrierType deleted?
        if ($carrierType->delete()) {
            // Redirect to the carrierType management page
            return Redirect::to('carrier-types')->with('success', Lang::get('carrier-type/messages.delete.success'));
        }

        // There was a problem deleting the carrierType
        return Redirect::to('carrier-types')->with('error', Lang::get('carrier-type/messages.delete.error'));
    }

    /**
     * Show a list of all the carrierTypes formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        //Make this method testable and mockable by using our injected $carrierType member.
        $carrierTypes = $this->carrierType->select(array('carrier_types.id',  'carrier_types.name', 'carrier_types.description', 'carrier_types.created_at'));

        return Datatables::of($carrierTypes)
        // ->edit_column('created_at','{{{ Carbon::now()->diffForHumans(Carbon::createFromFormat(\'Y-m-d H\', $test)) }}}')

        ->add_column('actions', '<div class="btn-group">
                  <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">
                    Action <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="{{{ URL::to(\'carrier-types/\' . $id ) }}}">{{{ Lang::get(\'button.show\') }}}</a></li>
                    <li><a href="{{{ URL::to(\'carrier-types/\' . $id . \'/edit\' ) }}}">{{{ Lang::get(\'button.edit\') }}}</a></li>
                    <li><a href="{{{ URL::to(\'carrier-types/\' . $id . \'/delete\' ) }}}">{{{ Lang::get(\'button.delete\') }}}</a></li>
                  </ul>
                </div>')

        ->remove_column('id')

        ->make();
    }
}
