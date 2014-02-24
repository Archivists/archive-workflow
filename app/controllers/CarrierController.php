<?php
/*
|--------------------------------------------------------------------------
| Carrier Controller
|--------------------------------------------------------------------------
|
| See routes.php  ->
| Route::resource('carrier', 'CarrierController');
| Route::get('carrier/{carrier}/delete', 'CarrierController@delete');
|
*/

class CarrierController extends BaseController
{
    /**
     * Carrier Model
     * @var Carrier
     */
    protected $carrier;

    /**
     * Status Model
     * @var Status
     */
    protected $status;

    /**
     * Category Model
     * @var Category
     */
    protected $category;

    /**
     * Inject the model.
     * @param Carrier $carrier
     */
    public function __construct(Carrier $carrier, Status $status, Category $category)
    {
        parent::__construct();
        $this->carrier = $carrier;
        $this->status = $status;
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * See public function data() below for the data source for the list,
     * and the view/carrier/index.blade.php for the jQuery script that makes
     * the Ajax request.
     *
     * @return Response
     */
    public function index()
    {
        // Title
        $title = Lang::get('carrier/title.carrier_management');

        $selectedStatus = Session::get('status', 'all'); //Default to 'all' if status is not yet set.
        $input = Input::get('status');

        if ($input && $input != $selectedStatus) {
            Session::put('status', $input);
            $selectedStatus = $input;
        }

         // All carrier types by sequence order (see Status model for scope)
        $statuses = $this->status->sequence()->get();

        // Show the page
        return View::make('carrier/index', compact('title', 'statuses', 'selectedStatus'));
    }

    /**
     * Show a single carrier details page.
     *
     * @return View
     */
    public function show($id)
    {
        $carrier = $this->carrier->find($id);

        if ($carrier->id) {
            // Title
            $title = Lang::get('carrier/title.carrier_show');

            // Show the page
            return View::make('carrier/show', compact('carrier', 'title'));

        } else {
            // Redirect to the carrier management page
            return Redirect::to('carriers')->with('error', Lang::get('carrier/messages.does_not_exist'));
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
        $title = Lang::get('carrier/title.create_a_new_carrier');

        // All carrier types
        $categories = $this->category->all();

        // Sides
        $sides = array("1" => 1, "2" => 2);
        
        // Selected side
        $selectedSide = Input::old('sides', array());

        // Selected carrier type
        $selectedType = Input::old('carrier-type', array());

        // Show the page
        return View::make('carrier/create', compact('title', 'sides', 'selectedSide', 'categories', 'selectedType'));
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
            'shelf_number'=> 'required|alpha_dash|unique:carriers,shelf_number',
            'sides' => 'required',
            'category'=> 'required'
            );
        
        // Validate the inputs
        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes()) {
            
            // Get the inputs, with some exceptions
            $inputs = Input::except('csrf_token');

            $category = $this->category->find($inputs['category']);
            $status = $this->status->where('order', 1)->first();

            if ($category && $status) {

                $this->carrier->shelf_number = $inputs['shelf_number'];
                $this->carrier->sides = $inputs['sides'];
                $this->carrier->notes = $inputs['notes'];
                $this->carrier->status()->associate($status);
                $this->carrier->category()->associate($category);

                if ($this->carrier->save($rules)) {
                    // Redirect to the new carrier page
                    return Redirect::to('carriers')->with('success', Lang::get('carrier/messages.create.success'));

                } else {
                    // Redirect to the carrier create page
                    //var_dump($this->carrier);
                    return Redirect::to('carriers/create')->with('error', Lang::get('carrier/messages.create.error'));
                }
            }
            else {
                // Redirect to the carrier create page
                //var_dump($this->carrier);
                return Redirect::to('carriers/create')->with('error', Lang::get('carrier/messages.create.error'));    
            }
        } else {
            // Form validation failed
            return Redirect::to('carriers/create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $carrier
     * @return Response
     */
    public function edit($id)
    {
        $carrier = $this->carrier->find($id);

        if ($carrier->id) {

        } else {
            // Redirect to the carrier management page
            return Redirect::to('carriers')->with('error', Lang::get('carrier/messages.does_not_exist'));
        }

        // Title
        $title = Lang::get('carrier/title.carrier_update');

        // Sides
        $sides = array("1" => 1, "2" => 2);

        // Selected side
        $selectedSide = Input::old('sides', 0);

        // All carrier types
        $categories = $this->category->all();

        // Selected carrier type
        $selectedType = Input::old('carrier-type', array());

        // Show the page
        return View::make('carrier/edit', compact('carrier', 'title', 'sides', 'selectedSide', 'categories', 'selectedType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $carrier
     * @return Response
     */
    public function update($id)
    {
        $carrier = $this->carrier->find($id);

        $rules = array(
                'shelf_number'=> 'required|alpha_dash|unique:carriers,shelf_number,' . $carrier->id,
                'sides' => 'required',
                'category'=> 'required'
            );

        // Validate the inputs
        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes()) {
            
            // Get the inputs, with some exceptions
            $inputs = Input::except('csrf_token');

            $category = $this->category->find($inputs['category']);
            

            if ($category) {

                $carrier->shelf_number = $inputs['shelf_number'];
                $carrier->sides = $inputs['sides'];
                $carrier->notes = $inputs['notes'];
                $carrier->category()->associate($category);

                // Was the carrier updated?
                if ($carrier->save($rules)) {
                    // Redirect to the carrier page
                    return Redirect::to('carriers/' . $carrier->id)->with('success', Lang::get('carrier/messages.update.success'));
                } else {
                    // Redirect to the carrier page
                    return Redirect::to('carriers/' . $carrier->id . '/edit')->with('error', Lang::get('carrier/messages.update.error'));
                }
            }
            else {
                return Redirect::to('carriers/' . $carrier->id . '/edit')->with('error', Lang::get('carrier/messages.update.error'));
            }
        } else {
            // Form validation failed
            return Redirect::to('carriers/' . $carrier->id . '/edit')->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove carrier page.
     *
     * @param $carrier
     * @return Response
     */
    public function delete($id)
    {
        $carrier = $this->carrier->find($id);

        // Title
        $title = Lang::get('carrier/title.carrier_delete');

        if ($carrier->id) {

        } else {
            // Redirect to the carrier management page
            return Redirect::to('carriers')->with('error', Lang::get('carrier/messages.does_not_exist'));
        }

        // Show the record
        return View::make('carrier/delete', compact('carrier', 'title'));
    }

    /**
     * Remove the specified carrier from storage.
     * @internal param $id
     * @return Response
     */
    public function destroy($id)
    {
        $carrier = $this->carrier->find($id);

        // Was the carrier deleted?
        if ($carrier->delete()) {
            // Redirect to the carrier management page
            return Redirect::to('carriers')->with('success', Lang::get('carrier/messages.delete.success'));
        }

        // There was a problem deleting the carrier
        return Redirect::to('carriers')->with('error', Lang::get('carrier/messages.delete.error'));
    }

    /**
     * Show a list of all the carriers formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        //Make this method testable and mockable by using our injected $carrier member.
        $carriers = $this->carrier
                ->workflow()
                ->leftjoin('status', 'status.id', '=', 'carriers.status_id')
                ->select(array('carriers.id', 'carriers.shelf_number', 'status.name as status', 'carriers.sides', 'carriers.created_at'))
                ->orderBy('carriers.created_at', 'desc');

        return Datatables::of($carriers)
        // ->edit_column('created_at','{{{ Carbon::now()->diffForHumans(Carbon::createFromFormat(\'Y-m-d H\', $test)) }}}')   

        //See below - need to change name of added column - archive_id is automatically added at the end.
        ->add_column('archive_no', '<a href="{{{ URL::to(\'carriers/\' . $id) }}}"> {{{ $archive_id }}} </a>', 0)

        ->add_column('actions', '<div class="btn-group">
                  <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">
                    Action <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="{{{ URL::to(\'carriers/\' . $id ) }}}">{{{ Lang::get(\'button.show\') }}}</a></li>
                    <li><a href="{{{ URL::to(\'carriers/\' . $id . \'/edit\' ) }}}">{{{ Lang::get(\'button.edit\') }}}</a></li>
                    <li><a href="{{{ URL::to(\'carriers/\' . $id . \'/delete\' ) }}}">{{{ Lang::get(\'button.delete\') }}}</a></li>
                  </ul>
                </div>')

        ->remove_column('id')

        //Weird - archive_id is automatically added at the end, and we had to add archive_no above.
        ->remove_column('archive_id')

        ->make();
    }
}
